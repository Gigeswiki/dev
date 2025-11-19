#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
VENV_DIR="$PROJECT_DIR/venv"
LOG_DIR="$PROJECT_DIR/logs"
mkdir -p "$LOG_DIR"

if [ ! -d "$VENV_DIR" ]; then
  python3 -m venv "$VENV_DIR"
fi

source "$VENV_DIR/bin/activate"

pip install --upgrade pip >/dev/null
pip install -r "$PROJECT_DIR/requirements.txt" gunicorn >/dev/null

cd "$PROJECT_DIR"

export FLASK_ENV=production
export PYTHONPATH="$PROJECT_DIR"

echo "Starting Gunicorn..."
exec gunicorn -c gunicorn_config.py main:app >>"$LOG_DIR/gunicorn.log" 2>&1
