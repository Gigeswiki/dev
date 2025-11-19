# AI Coding Agent Instructions for This Codebase

## Big Picture Architecture
This is a Flask-based web application for card/payment processing with an admin panel. It uses SQLite locally and PostgreSQL on Heroku, with Cloudflare integration for DNS, SSL, and tunneling. Key components:
- **Database Layer** (`app/database.py`): Dual support for SQLite/PostgreSQL with schema patches for compatibility.
- **Routes** (`app/routes/`): Blueprints for admin, public, and binlookup endpoints.
- **Services** (`app/services/`): Cloudflare API integrations for DNS, SSL, tunnels, and domain aliases.
- **Core App** (`main.py`): Gunicorn-wrapped Flask app with dotenv config.
- Data flows from user inputs (e.g., card details) through validation/detection (`app/detectors.py`, `app/binlookup.py`) to database storage and admin logging.

## Critical Developer Workflows
- **Local Development**: Run `python main.py` (uses SQLite by default). Use `venv/` for isolation.
- **Database Migration**: Use `full_dump_sqlite_to_postgres.py` to convert SQLite to PostgreSQL SQL dump (`sqlite_structure_postgres.txt`), then import with `import_data.py`.
- **Deployment**: Push to Heroku with `git push heroku main`. Ensure `DATABASE_URL` is set for PostgreSQL. Fix buildpacks: `heroku buildpacks:set heroku/python`.
- **Debugging**: Check logs in `logs/gunicorn.log` or Heroku logs. Use `app/logs.html` for in-app logging.

## Project-Specific Conventions
- **Database Queries**: Use `fetch_one()`, `fetch_all()`, `execute()` from `app/database.py`. Placeholders use `?` for SQLite, `%s` for PostgreSQL.
- **Error Handling**: Log errors but don't raise; use try-except in routes (e.g., `app/routes/admin.py`).
- **Encryption**: Use `app/encryption.py` for sensitive data; avoid plain text storage.
- **IP Blocking**: Integrate `app/ip_blocker.py` for security.
- **Templates**: Jinja2 with base.html; admin UI uses custom CSS/JS in `static/admin/`.

## Integration Points and External Dependencies
- **Cloudflare API**: Auth via API key; manage domains/tunnels in `app/services/cloudflare_*.py`.
- **PostgreSQL**: RDS or Heroku; handle read-only errors by enabling writes in AWS or switching to Heroku Postgres.
- **User Agents/Browsers**: Detect with `user-agents` library in routes.
- **Gunicorn**: Production server; config in scripts.

## Examples
- Adding a route: Register in `app/routes/public.py` with `@public_bp.route('/new')`.
- Database insert: `execute("INSERT INTO sazan (tc, ip) VALUES (?, ?)", [tc, ip])`.
- Service call: `from app.services.cloudflare_dns import update_dns; update_dns(domain)`.

Reference: `app/database.py` for DB patterns, `app/routes/admin.py` for admin logic, `scripts/` for deployment helpers.
