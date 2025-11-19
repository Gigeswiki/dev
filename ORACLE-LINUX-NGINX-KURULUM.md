# Oracle Linux 9.5 - Nginx Kurulum ve Yapılandırma Kılavuzu

## İçindekiler
1. [Nginx Kurulumu](#1-nginx-kurulumu)
2. [Nginx Servis Yönetimi](#2-nginx-servis-yönetimi)
3. [Kendi Web Sitenizi Oluşturma](#3-kendi-web-sitenizi-oluşturma)
4. [Virtual Host Kurulumu](#4-virtual-host-kurulumu)
5. [Virtual Host Aktivasyonu ve Test](#5-virtual-host-aktivasyonu-ve-test)
6. [Güvenlik Duvarı Ayarları](#6-güvenlik-duvarı-ayarları)
7. [SSL/HTTPS Yapılandırması](#7-sslhttps-yapılandırması)

---

## 1. Nginx Kurulumu

### Adım 1: Sistem Güncellemesi
Öncelikle sisteminizi güncelleyin:

```bash
sudo dnf update -y
```

### Adım 2: Nginx Paketini Yükleme
Oracle Linux 9.5 için Nginx'i yükleyin:

```bash
sudo dnf install nginx -y
```

### Adım 3: Nginx Versiyonunu Kontrol Etme
Kurulumu doğrulayın:

```bash
nginx -v
```

Çıktı örneği:
```
nginx version: nginx/1.20.1
```

---

## 2. Nginx Servis Yönetimi

### Nginx'i Başlatma
```bash
sudo systemctl start nginx
```

### Nginx'i Sistem Açılışında Otomatik Başlatma
```bash
sudo systemctl enable nginx
```

### Nginx Durumunu Kontrol Etme
```bash
sudo systemctl status nginx
```

### Nginx'i Yeniden Başlatma
```bash
sudo systemctl restart nginx
```

### Nginx Yapılandırmasını Yeniden Yükleme (Downtime Olmadan)
```bash
sudo systemctl reload nginx
```

### Nginx'i Durdurma
```bash
sudo systemctl stop nginx
```

---

## 3. Kendi Web Sitenizi Oluşturma

### Varsayılan Sayfa Konumu
Varsayılan sayfa `/usr/share/nginx/html/` konumundadır. Statik sayfalarınızı buraya yerleştirebilir veya virtual host kullanarak başka bir konuma yerleştirebilirsiniz.

### Özel Web Dizini Oluşturma
Kendi web siteniz için özel bir dizin oluşturalım:

```bash
# Web dizini oluşturma
sudo mkdir -p /var/www/tutorial

# Dizin sahipliğini ayarlama
sudo chown -R $USER:$USER /var/www/tutorial

# Dizin izinlerini ayarlama
sudo chmod -R 755 /var/www
```

### Örnek HTML Sayfası Oluşturma
```bash
# Dizine geçiş
cd /var/www/tutorial

# index.html dosyası oluşturma
sudo vi index.html
```

Aşağıdaki içeriği `index.html` dosyasına yapıştırın:

```html
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Merhaba, Nginx!</title>
</head>
<body>
    <h1>Merhaba, Nginx!</h1>
    <p>Oracle Linux 9.5 üzerinde Nginx web sunucusunu yapılandırdık!</p>
    <p>Bu örnek bir virtual host yapılandırmasıdır.</p>
</body>
</html>
```

Dosyayı kaydedin (vi editöründe: `ESC` tuşuna basın, ardından `:wq` yazıp `ENTER`).

---

## 4. Virtual Host Kurulumu

Virtual host, aynı sunucuda birden fazla alan adı barındırma yöntemidir.

### Adım 1: Virtual Host Yapılandırma Dosyası Oluşturma

```bash
# Yapılandırma dizinine geçiş
cd /etc/nginx/conf.d/

# Yeni virtual host dosyası oluşturma
sudo vi tutorial.conf
```

### Adım 2: Virtual Host Yapılandırması

Aşağıdaki yapılandırmayı `tutorial.conf` dosyasına ekleyin:

```nginx
server {
    listen 80;
    listen [::]:80;

    # Alan adınızı veya IP adresinizi buraya yazın
    server_name tutorial.example.com;

    # Kök dizin
    root /var/www/tutorial;
    index index.html index.htm;

    # Erişim ve hata logları
    access_log /var/log/nginx/tutorial.access.log;
    error_log /var/log/nginx/tutorial.error.log;

    # Ana lokasyon bloğu
    location / {
        try_files $uri $uri/ =404;
    }

    # Güvenlik başlıkları
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Gzip sıkıştırma
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json;
}
```

**Not:** `server_name` değerini kendi alan adınız veya sunucu IP'niz ile değiştirin.

### Adım 3: SELinux İçin İzin Ayarları (Önemli!)

Oracle Linux 9.5'te SELinux varsayılan olarak aktiftir. Web dizini için doğru SELinux context ayarlanmalı:

```bash
# SELinux context ayarlama
sudo semanage fcontext -a -t httpd_sys_content_t "/var/www/tutorial(/.*)?"
sudo restorecon -Rv /var/www/tutorial

# Nginx'in ağ bağlantıları yapmasına izin verme
sudo setsebool -P httpd_can_network_connect 1
```

Eğer `semanage` komutu bulunamazsa:

```bash
sudo dnf install policycoreutils-python-utils -y
```

---

## 5. Virtual Host Aktivasyonu ve Test

### Adım 1: Nginx Yapılandırmasını Test Etme

```bash
sudo nginx -t
```

Başarılı çıktı:
```
nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
nginx: configuration file /etc/nginx/nginx.conf test is successful
```

### Adım 2: Nginx'i Yeniden Yükleme

```bash
sudo systemctl reload nginx
```

### Adım 3: Yapılandırmayı Test Etme

**Local test (sunucu üzerinde):**
```bash
curl -I http://localhost
```

**Web tarayıcısından test:**
- Tarayıcınızda şu adresi açın: `http://SUNUCU_IP_ADRESI`
- Veya alan adınızı yapılandırdıysanız: `http://tutorial.example.com`

### Adım 4: Log Dosyalarını Kontrol Etme

```bash
# Erişim logları
sudo tail -f /var/log/nginx/tutorial.access.log

# Hata logları
sudo tail -f /var/log/nginx/tutorial.error.log
```

---

## 6. Güvenlik Duvarı Ayarları

### Firewalld Yapılandırması

```bash
# HTTP trafiğine izin verme
sudo firewall-cmd --permanent --add-service=http

# HTTPS trafiğine izin verme (SSL kullanacaksanız)
sudo firewall-cmd --permanent --add-service=https

# Firewall'u yeniden yükleme
sudo firewall-cmd --reload

# Açık portları kontrol etme
sudo firewall-cmd --list-all
```

### Alternatif: Belirli portları açma

```bash
sudo firewall-cmd --permanent --add-port=80/tcp
sudo firewall-cmd --permanent --add-port=443/tcp
sudo firewall-cmd --reload
```

---

## 7. SSL/HTTPS Yapılandırması

### Ücretsiz SSL Sertifikası (Let's Encrypt)

#### Adım 1: Certbot Kurulumu

```bash
# EPEL repository'yi etkinleştirme
sudo dnf install epel-release -y

# Certbot ve Nginx eklentisi kurulumu
sudo dnf install certbot python3-certbot-nginx -y
```

#### Adım 2: SSL Sertifikası Alma

```bash
sudo certbot --nginx -d tutorial.example.com
```

Sertifika otomatik olarak yenilenecek şekilde yapılandırılır.

#### Adım 3: Otomatik Yenileme Testi

```bash
sudo certbot renew --dry-run
```

### Manuel SSL Yapılandırması (Kendi Sertifikanız Varsa)

```nginx
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    
    server_name tutorial.example.com;
    
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_prefer_server_ciphers on;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256;
    
    root /var/www/tutorial;
    index index.html;
    
    location / {
        try_files $uri $uri/ =404;
    }
}

# HTTP'den HTTPS'e yönlendirme
server {
    listen 80;
    listen [::]:80;
    server_name tutorial.example.com;
    return 301 https://$server_name$request_uri;
}
```

---

## Yararlı Komutlar

### Nginx Yapılandırma Dosyalarını Kontrol Etme
```bash
# Ana yapılandırma dosyası
sudo vi /etc/nginx/nginx.conf

# Virtual host dosyaları
ls -la /etc/nginx/conf.d/

# Varsayılan site yapılandırması
sudo vi /etc/nginx/conf.d/default.conf
```

### Nginx Proseslerini Görüntüleme
```bash
ps aux | grep nginx
```

### Nginx Portlarını Kontrol Etme
```bash
sudo netstat -tulpn | grep nginx
# veya
sudo ss -tulpn | grep nginx
```

### Tüm Nginx Loglarını Görüntüleme
```bash
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log
```

---

## Sorun Giderme

### Problem: "Permission denied" Hatası

**Çözüm 1 - SELinux izinleri:**
```bash
sudo semanage fcontext -a -t httpd_sys_content_t "/var/www/tutorial(/.*)?"
sudo restorecon -Rv /var/www/tutorial
```

**Çözüm 2 - Dosya izinleri:**
```bash
sudo chmod -R 755 /var/www/tutorial
sudo chown -R nginx:nginx /var/www/tutorial
```

### Problem: "Port 80 already in use"

**Çözüm:** Portu kullanan servisi bulun ve durdurun:
```bash
sudo netstat -tulpn | grep :80
sudo systemctl stop httpd  # Apache çalışıyorsa
```

### Problem: "Connection refused"

**Çözüm:** Firewall ve SELinux kontrol edin:
```bash
sudo systemctl status firewalld
sudo getenforce
sudo firewall-cmd --list-all
```

### Problem: 403 Forbidden Hatası

**Çözüm:**
```bash
# index.html dosyasının var olduğunu kontrol edin
ls -la /var/www/tutorial/

# SELinux context'ini kontrol edin
ls -Z /var/www/tutorial/

# Nginx error log'unu inceleyin
sudo tail -50 /var/log/nginx/error.log
```

---

## Bulut Sunucuya Özel Notlar

### Oracle Cloud Infrastructure (OCI)

1. **Security List / Network Security Group:**
   - OCI Console'da VCN (Virtual Cloud Network) ayarlarından
   - Ingress Rules'a HTTP (80) ve HTTPS (443) portlarını ekleyin

2. **Instance Firewall:**
   ```bash
   # iptables kurallarını kontrol edin
   sudo iptables -L -n -v
   ```

### Genel Bulut Sağlayıcılar

- **AWS:** Security Groups'ta port 80 ve 443'ü açın
- **Azure:** Network Security Groups'ta inbound rules ekleyin
- **Google Cloud:** Firewall rules oluşturun

---

## Performans Optimizasyonu

### Worker Processes Ayarı

```bash
sudo vi /etc/nginx/nginx.conf
```

```nginx
# CPU çekirdek sayınıza göre ayarlayın
worker_processes auto;

events {
    worker_connections 1024;
}
```

### Client Buffer Boyutları

```nginx
http {
    client_body_buffer_size 10K;
    client_header_buffer_size 1k;
    client_max_body_size 8m;
    large_client_header_buffers 2 1k;
}
```

### Timeout Değerleri

```nginx
http {
    client_body_timeout 12;
    client_header_timeout 12;
    keepalive_timeout 15;
    send_timeout 10;
}
```

---

## Güvenlik En İyi Uygulamaları

1. **Nginx versiyonunu gizleyin:**
```nginx
http {
    server_tokens off;
}
```

2. **Rate limiting ekleyin:**
```nginx
http {
    limit_req_zone $binary_remote_addr zone=one:10m rate=1r/s;
    
    server {
        location / {
            limit_req zone=one burst=5;
        }
    }
}
```

3. **DDoS koruması için connection limiting:**
```nginx
http {
    limit_conn_zone $binary_remote_addr zone=addr:10m;
    
    server {
        limit_conn addr 10;
    }
}
```

---

## Yedekleme ve Bakım

### Yapılandırma Yedekleme
```bash
# Tüm Nginx yapılandırmalarını yedekleme
sudo tar -czf nginx-config-backup-$(date +%Y%m%d).tar.gz /etc/nginx/

# Belirli bir siteyi yedekleme
sudo tar -czf tutorial-backup-$(date +%Y%m%d).tar.gz /var/www/tutorial/
```

### Log Rotasyonu
Oracle Linux'ta logrotate otomatik olarak yapılandırılmıştır. Özel ayarlar için:

```bash
sudo vi /etc/logrotate.d/nginx
```

---

## Özet: Hızlı Başlangıç Komutları

```bash
# 1. Nginx kurulumu
sudo dnf update -y
sudo dnf install nginx -y

# 2. Nginx'i başlatma ve etkinleştirme
sudo systemctl start nginx
sudo systemctl enable nginx

# 3. Web dizini oluşturma
sudo mkdir -p /var/www/tutorial
sudo chown -R $USER:$USER /var/www/tutorial

# 4. Örnek sayfa oluşturma
echo '<!doctype html><html><head><title>Test</title></head><body><h1>Nginx Çalışıyor!</h1></body></html>' | sudo tee /var/www/tutorial/index.html

# 5. Virtual host yapılandırması
sudo vi /etc/nginx/conf.d/tutorial.conf

# 6. SELinux izinleri
sudo dnf install policycoreutils-python-utils -y
sudo semanage fcontext -a -t httpd_sys_content_t "/var/www/tutorial(/.*)?"
sudo restorecon -Rv /var/www/tutorial

# 7. Firewall ayarları
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload

# 8. Yapılandırmayı test etme ve yenileme
sudo nginx -t
sudo systemctl reload nginx
```

---

## Başarı Kontrol Listesi

- [ ] Nginx başarıyla kuruldu
- [ ] Nginx servisi çalışıyor ve sistem açılışında otomatik başlayacak
- [ ] Web dizini oluşturuldu ve izinler ayarlandı
- [ ] Virtual host yapılandırması oluşturuldu
- [ ] SELinux context'leri doğru ayarlandı
- [ ] Firewall kuralları eklendi
- [ ] Tarayıcıdan siteye erişim sağlandı
- [ ] SSL sertifikası kuruldu (isteğe bağlı)
- [ ] Log dosyaları düzgün çalışıyor

---

**Not:** Bu kılavuz Oracle Linux 9.5 için optimize edilmiştir. Diğer Linux dağıtımlarında (Ubuntu, Debian, CentOS) bazı komutlar farklılık gösterebilir.

**Yardım ve Destek:**
- Nginx Resmi Dokümantasyonu: https://nginx.org/en/docs/
- Oracle Linux Dokümantasyonu: https://docs.oracle.com/en/operating-systems/oracle-linux/

---

*Son güncelleme: 19 Kasım 2025*
