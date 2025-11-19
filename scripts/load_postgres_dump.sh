#!/usr/bin/env bash
# Load the bundled PostgreSQL dump and grant the connected user all privileges to avoid permission blockers.
set -euo pipefail

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DUMP_FILE="$PROJECT_DIR/postgresql_dump.sql"

if ! command -v psql >/dev/null 2>&1; then
  echo "[load-postgres] 'psql' command not found. Install the PostgreSQL client before running this script." >&2
  exit 1
fi

CONNECTION="${1:-${DATABASE_URL:-}}"
if [[ -z "$CONNECTION" ]]; then
  echo "[load-postgres] Provide a PostgreSQL connection string as the first argument or in DATABASE_URL." >&2
  exit 1
fi

if [[ ! -f "$DUMP_FILE" ]]; then
  echo "[load-postgres] Dump file not found at $DUMP_FILE" >&2
  exit 1
fi

echo "[load-postgres] Loading dump into the database from '$DUMP_FILE'..."
psql "$CONNECTION" -f "$DUMP_FILE"

CURRENT_USER=$(psql "$CONNECTION" -tA -c "SELECT current_user;" | tr -d '[:space:]')
CURRENT_DB=$(psql "$CONNECTION" -tA -c "SELECT current_database();" | tr -d '[:space:]')

if [[ -z "$CURRENT_USER" || -z "$CURRENT_DB" ]]; then
  echo "[load-postgres] Could not determine the current user/database. Privilege grants skipped." >&2
  exit 1
fi

echo "[load-postgres] Granting all privileges to user '$CURRENT_USER' on database '$CURRENT_DB'..."
psql "$CONNECTION" <<SQL
GRANT ALL PRIVILEGES ON DATABASE "$CURRENT_DB" TO "$CURRENT_USER";
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO "$CURRENT_USER";
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO "$CURRENT_USER";
GRANT ALL PRIVILEGES ON ALL FUNCTIONS IN SCHEMA public TO "$CURRENT_USER";
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL PRIVILEGES ON TABLES TO "$CURRENT_USER";
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL PRIVILEGES ON SEQUENCES TO "$CURRENT_USER";
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL PRIVILEGES ON FUNCTIONS TO "$CURRENT_USER";
SQL

echo "[load-postgres] Import and grants completed. Use 'psql <connection>' to verify data/state." 