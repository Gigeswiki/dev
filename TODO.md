# TODO: SQLite to PostgreSQL Migration and Heroku Deployment

## 1. Create Full Dump Script
- [x] Create `full_dump_sqlite_to_postgres.py` to dump schema and data from SQLite, convert to PostgreSQL syntax, and write to `sqlite_structure_postgres.txt`.

## 2. Update .gitignore
- [ ] Add `venv/` to `.gitignore` to exclude virtual environment.

## 3. Run Dump Script
- [x] Execute `python full_dump_sqlite_to_postgres.py` to generate the full PostgreSQL-compatible SQL dump.

## 4. Fix Heroku Buildpack
- [ ] Use Heroku CLI to set buildpack to Python only: `heroku buildpacks:set heroku/python -a <app-name>`.

## 5. Deploy to Heroku
- [ ] Commit changes and push to Heroku: `git add .`, `git commit -m "Fix venv and buildpack"`, `git push heroku main`.

## 6. Address Read-Only Transaction Error
- [ ] Check RDS PostgreSQL settings to ensure writes are enabled (not read-only replica).
- [ ] Alternatively, switch to Heroku's provided DATABASE_URL if using Heroku Postgres.
