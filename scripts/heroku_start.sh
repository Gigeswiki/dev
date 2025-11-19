#!/usr/bin/env bash
# Helper script for Heroku so the web dyno binds to the platform-provided PORT and respects other runtime knobs.
set -euo pipefail

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$PROJECT_DIR"

if ! command -v gunicorn >/dev/null 2>&1; then
  echo "[heroku-start] gunicorn is missing. Ensure your dependencies were installed (pip install -r requirements.txt)." >&2
  exit 1
fi

PORT="${PORT:-5000}"
WEB_CONCURRENCY="${WEB_CONCURRENCY:-2}"
LOG_LEVEL="${LOG_LEVEL:-info}"
TIMEOUT="${TIMEOUT:-120}"
KEEP_ALIVE="${KEEP_ALIVE:-5}"

if [ -z "${DATABASE_URL:-}" ]; then
  echo "[heroku-start] WARNING: DATABASE_URL is unset. Heroku Postgres is recommended; falling back to db.sqlite3 if available." >&2
fi

export PYTHONUNBUFFERED=1
export PYTHONPATH="$PROJECT_DIR"

echo "[heroku-start] Gunicorn will listen on 0.0.0.0:${PORT} using ${WEB_CONCURRENCY} worker(s) at log level ${LOG_LEVEL}."

exec gunicorn main:app \
  --bind "0.0.0.0:${PORT}" \
  --workers "$WEB_CONCURRENCY" \
  --timeout "$TIMEOUT" \
  --keep-alive "$KEEP_ALIVE" \
  --log-level "$LOG_LEVEL" \
  --access-logfile "-" \
  --error-logfile "-" \
  ${GUNICORN_EXTRA_ARGS:-}
