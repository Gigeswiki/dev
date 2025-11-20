# dev

Minimal Flask uygulamamız, PostgreSQL desteğini Heroku üzerinde çalışacak şekilde yapılandırılmıştır.

## Heroku ortamı

- `runtime.txt` içinde `python-3.11.5` olduğu için Heroku buildpack doğru Python sürümünü kurar.
- `requirements.txt` içinde `gunicorn`, `psycopg[binary]` gibi üretim bağımlılıkları tanımlıdır.
- `Procfile`, `scripts/heroku_start.sh` üzerinden `gunicorn main:app` komutunu çalıştırır ve PORT ile log seviyesini ortamdan alır.
- `scripts/heroku_start.sh`, `DATABASE_URL` tanımlı değilse `db.sqlite3` dosyasına geri döner ancak üretimde Heroku Postgres tavsiye edilir.

## Dağıtım adımları

1. Heroku CLI ile buildpack'i yalnızca Python olacak şekilde ayarlayın:
   ```shell
   heroku buildpacks:set heroku/python -a <app-name>
   ```
2. En az `DATABASE_URL`, tercihen `FLASK_SECRET_KEY` gibi ortam değişkenlerini Heroku ayarlarına girin.
3. Kod değişikliklerini commit edip Heroku'ya gönderin:
   ```shell
   git add .
   git commit -m "Prepare Heroku build"
   git push heroku main
   ```
4. Deploy sırasında `python runtime` ve `gunicorn` loglarını takip edin; `DATABASE_URL` yoksa script bir uyarı verir ve `db.sqlite3` kullanır.
5. Gerekirse `heroku logs --tail` ile dinamik loglara bakın.

## Notlar

- Yerel geliştirmede `python main.py` ile SQLite modunda çalışır.
- Veritabanı geçişi için `full_dump_sqlite_to_postgres.py` ve `import_data.py` komutlarını kullanabilirsiniz.