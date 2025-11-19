import sqlite3
from typing import Any, TextIO

def get_column_names(cursor: sqlite3.Cursor, table_name: str) -> list[str]:
    """Get column names for a given table."""
    cursor.execute(f"PRAGMA table_info({table_name})")
    columns = cursor.fetchall()
    return [col[1] for col in columns]

def format_value(val: Any) -> str:
    """Format a single value for SQL INSERT statement."""
    if val is None:
        return 'NULL'
    elif isinstance(val, str):
        escaped_val = val.replace("'", "''")
        return f"'{escaped_val}'"
    else:
        return str(val)

def create_insert_statement(table_name: str, col_names: list[str], row: tuple[Any, ...]) -> str:
    """Create an INSERT statement for a single row."""
    values = [format_value(val) for val in row]
    return f"INSERT INTO {table_name} ({', '.join(col_names)}) VALUES ({', '.join(values)});"

def dump_table_data(cursor: sqlite3.Cursor, table_name: str, f: TextIO) -> None:
    """Dump data for a single table."""
    col_names = get_column_names(cursor, table_name)
    cursor.execute(f"SELECT * FROM {table_name}")
    rows = cursor.fetchall()
    
    if rows:
        for row in rows:
            insert_stmt = create_insert_statement(table_name, col_names, row)
            f.write(insert_stmt + "\n")
    f.write("\n")

def dump_sqlite_data_only(db_path: str, output_file: str) -> None:
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()

    with open(output_file, 'w') as f:
        cursor.execute("SELECT name FROM sqlite_master WHERE type='table';")
        tables = cursor.fetchall()

        for table in tables:
            table_name = table[0]
            if table_name == 'sqlite_sequence':
                continue
            dump_table_data(cursor, table_name, f)

    conn.close()

if __name__ == "__main__":
    dump_sqlite_data_only('db.sqlite3', 'postgresql_data_only.sql')
