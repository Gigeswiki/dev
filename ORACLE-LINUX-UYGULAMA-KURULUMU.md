# Oracle Linux 9.5 Üzerine Python (Flask) Uygulaması Kurulum Kılavuzu

Bu belge, `ekart-dolum-iade-dev` Flask uygulamasını Oracle Linux 9.5 üzerinde üretim ortamına kurmak için gereken tüm adımları içerir. Süreç; sistem hazırlığı, bağımlılıklar, uygulama kurulumu, Gunicorn + systemd servis konfigürasyonu, Nginx reverse proxy, SELinux ve güvenlik ayarlarını kapsar.

---

## 1. Sistem Hazırlığı

### 1.1 Sunucu Güncellemesi
```bash
sudo dnf update -y
sudo dnf install -y oracle-epel-release-el9
sudo dnf upgrade -y
```

### 1.2 Gerekli Paketler
```bash
sudo dnf install -y \
  git python3 python3-pip python3-virtualenv \
  gcc g++ make \
  libffi-devel openssl-devel \
  nginx policycoreutils-python-utils firewalld
```

> **Not:** Python 3.11 gerekiyorsa `sudo dnf module enable python:3.11` ardından `sudo dnf install python3.11 python3.11-devel` komutlarını çalıştırabilirsiniz.

### 1.3 Uygulama Kullanıcısı (Opsiyonel)
```bash
sudo useradd --system --create-home --shell /bin/bash ekart
sudo passwd -l ekart
```

---

## 2. Proje Kurulumu

### 2.1 Dizinler ve Yetkiler
```bash
sudo mkdir -p /var/www/ekart
sudo chown -R $USER:$USER /var/www/ekart
cd /var/www/ekart
```

### 2.2 Kaynak Kodun Alınması
```bash
git clone https://github.com/<REPO>/ekart-dolum-iade-dev.git .
```

Yerel kopyayı taşıyorsanız `rsync` ile `/home/ayzio/ekart-dolum-iade-dev/` içeriğini `/var/www/ekart/` dizinine aktarabilirsiniz.

### 2.3 Sanal Ortam ve Bağımlılıklar
```bash
python3 -m venv venv
source venv/bin/activate
pip install --upgrade pip wheel
pip install -r requirements.txt
```

### 2.4 .env Dosyası
```bash
cp .env.example .env
vi .env  # Gerekli değerleri doldurun
```

Önemli değişkenler:
- `FLASK_SECRET_KEY`
- `DATABASE_URL` (ör. Neon PostgreSQL bağlantı dizesi: `postgresql://user:pass@host/db?sslmode=require`)
- `DATABASE_PATH` (yalnızca lokal SQLite fallback kullanacaksanız; varsayılan `db.sqlite3`)
- Cloudflare veya IP engelleme ayarları

### 2.5 Public IP ve Cloudflare DNS Senkronizasyonu
Sunucunuzun IP adresi değiştikçe `.env` dosyası ve Cloudflare DNS kayıtlarının güncel kalması gerekir. Reponun kökünde yer alan script otomatik olarak bu işlemleri yapar:

```bash
cd /var/www/ekart
source venv/bin/activate
python scripts/update_public_ip.py
```

- Script `.env` içindeki `SERVER_PUBLIC_IP` değerini yazar, admin panelinde gösterilen `public_ip` ayarını günceller ve `CLOUDFLARE_DNS_HOSTS` (tanımlı değilse `CLOUDFLARE_SSL_HOSTS`) listesinde yer alan tüm hostlar için Cloudflare DNS A kayıtlarını yeni IP'ye çevirir.
- Cron ile her saat otomatik çalıştırmak için örnek:

```bash
(crontab -l 2>/dev/null; echo "0 * * * * cd /var/www/ekart && source venv/bin/activate && python scripts/update_public_ip.py") | crontab -
```

- Cloudflare API bilgileri `.env` dosyasında tanımlı olmalıdır (`CLOUDFLARE_AUTH_EMAIL` + `CLOUDFLARE_AUTH_KEY` veya `CLOUDFLARE_API_TOKEN`, ayrıca `CLOUDFLARE_ZONE_ID`).

### 2.6 Veritabanı ve İzinler
PostgreSQL/Neon kullanıyorsanız bu adımı atlayabilirsiniz. Lokal SQLite tercih ediyorsanız:
```bash
touch db.sqlite3
sudo chown -R ekart:ekart /var/www/ekart
sudo chmod 640 db.sqlite3
```

---

## 3. Gunicorn + systemd Servisi

### 3.1 Dizin Yapısı
```
/var/www/ekart
├── venv/
├── main.py
├── gunicorn_config.py
├── db.sqlite3
└── logs/
```

### 3.2 Log Dizini
```bash
sudo mkdir -p /var/log/ekart
sudo chown -R ekart:ekart /var/log/ekart
```

`gunicorn_config.py` içinde log yollarını `/var/log/ekart/gunicorn-access.log` ve `/var/log/ekart/gunicorn-error.log` şeklinde güncellemeniz tavsiye edilir.

### 3.3 systemd Servis Dosyası
`/etc/systemd/system/ekart.service`:
```ini
[Unit]
Description=Ekart Flask Uygulaması (Gunicorn)
After=network.target

[Service]
User=ekart
Group=ekart
WorkingDirectory=/var/www/ekart
Environment="PATH=/var/www/ekart/venv/bin"
EnvironmentFile=/var/www/ekart/.env
ExecStart=/var/www/ekart/venv/bin/gunicorn -c gunicorn_config.py main:app
Restart=always
RestartSec=5
KillSignal=SIGTERM
TimeoutStopSec=30

[Install]
WantedBy=multi-user.target
```

### 3.4 Servisi Etkinleştirme
```bash
sudo systemctl daemon-reload
sudo systemctl enable --now ekart.service
sudo systemctl status ekart.service
```

Log inceleme:
```bash
sudo journalctl -u ekart.service -f
```

---

## 4. Nginx Reverse Proxy

### 4.1 Konfigürasyon Dosyası
`/etc/nginx/conf.d/ekart.conf`:
```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;

    # BTK / IP engelleme (opsiyonel)
    # deny 185.67.32.0/22;

    client_max_body_size 10M;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;

    location /static/ {
        alias /var/www/ekart/static/;
        expires 30d;
    }

    location / {
        proxy_pass http://127.0.0.1:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_redirect off;
    }
}
```

### 4.2 Nginx Test ve Restart
```bash
sudo nginx -t
sudo systemctl enable --now nginx
sudo systemctl restart nginx
sudo systemctl status nginx
```

---

## 5. SELinux ve İzinler

### 5.1 Web Dizinleri İçin Context
```bash
sudo semanage fcontext -a -t httpd_sys_content_t "/var/www/ekart(/.*)?"
sudo restorecon -Rv /var/www/ekart
```

### 5.2 Log Dizinleri
```bash
sudo semanage fcontext -a -t httpd_log_t "/var/log/ekart(/.*)?"
sudo restorecon -Rv /var/log/ekart
```

### 5.3 Gunicorn’un Ağ Erişimi
```bash
sudo setsebool -P httpd_can_network_connect 1
```

---

## 6. Firewall Ayarları

```bash
sudo systemctl enable --now firewalld
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload
sudo firewall-cmd --list-all
```

---

## 7. SSL / HTTPS

### 7.1 Certbot Kurulumu
```bash
sudo dnf install -y certbot python3-certbot-nginx
```

### 7.2 Sertifika Alma
```bash
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
sudo certbot renew --dry-run
```

### 7.3 HTTP → HTTPS Yönlendirmesi
```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    return 301 https://$host$request_uri;
}
```

---

## 8. İzleme ve Bakım

### 8.1 Servis Durumu
```bash
sudo systemctl status ekart
sudo systemctl status nginx
```

### 8.2 Loglar
```bash
sudo tail -f /var/log/ekart/gunicorn-error.log
sudo tail -f /var/log/ekart/gunicorn-access.log
sudo tail -f /var/log/nginx/error.log
```

### 8.3 Güncellemeler
```bash
cd /var/www/ekart
git pull origin main
source venv/bin/activate
pip install -r requirements.txt
sudo systemctl restart ekart
```

### 8.4 Yedekleme
```bash
sudo tar -czf /backup/ekart-$(date +%Y%m%d).tar.gz /var/www/ekart
sudo cp /var/www/ekart/db.sqlite3 /backup/db-$(date +%Y%m%d).sqlite3
```

---

## 9. Hızlı Kontrol Listesi

- [ ] Sunucu güncellendi ve bağımlılıklar kuruldu
- [ ] Proje `/var/www/ekart` altında
- [ ] Sanal ortam oluşturuldu ve `pip install -r requirements.txt` çalıştı
- [ ] `.env` içindeki gizli anahtar ve ayarlar güncellendi
- [ ] `db.sqlite3` doğru izinlerle mevcut
- [ ] `ekart.service` aktif ve çalışıyor
- [ ] Nginx reverse proxy yapılandırıldı
- [ ] SELinux ve firewall kuralları uygulandı
- [ ] SSL sertifikası kuruldu (opsiyonel)
- [ ] Log ve izleme komutları doğrulandı

---

## 10. Sorun Giderme

| Sorun | Kontrol | Komut |
| --- | --- | --- |
| Gunicorn başlamıyor | systemd logları | `sudo journalctl -u ekart -xe` |
| 502 Bad Gateway | Gunicorn portu | `sudo ss -tulpn | grep 8000` |
| Permission denied | SELinux context | `ls -Z /var/www/ekart` |
| SSL hatası | Certbot logları | `sudo tail -f /var/log/letsencrypt/letsencrypt.log` |
| Yavaş cevap | Worker sayısı | `workers = cpu*2+1` (gunicorn_config) |

---

**Son Güncelleme:** 19 Kasım 2025
