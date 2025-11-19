import sqlite3
import re

def convert_sqlite_to_postgres(sqlite_sql):
    # Convert AUTOINCREMENT to SERIAL
    postgres_sql = re.sub(r'INTEGER PRIMARY KEY AUTOINCREMENT', 'BIGSERIAL PRIMARY KEY', sqlite_sql, flags=re.IGNORECASE)
    # Convert INTEGER to BIGINT for ids if needed, but keep as is for now
    # Adjust timestamps: SQLite uses TEXT for dates, PostgreSQL can use TIMESTAMPTZ
    postgres_sql = re.sub(r'created_at TEXT DEFAULT CURRENT_TIMESTAMP', 'created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP', postgres_sql, flags=re.IGNORECASE)
    return postgres_sql

def dump_sqlite_to_postgres(db_path, output_file):
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()

    with open(output_file, 'w', encoding='utf-8') as f:
        # Get all table names
        cursor.execute("SELECT name FROM sqlite_master WHERE type='table';")
        tables = cursor.fetchall()

        for table in tables:
            table_name = table[0]
            # Skip sqlite_sequence if exists
            if table_name == 'sqlite_sequence':
                continue

            # Get CREATE TABLE statement
            cursor.execute(f"SELECT sql FROM sqlite_master WHERE type='table' AND name='{table_name}';")
            create_sql = cursor.fetchone()[0]
            if create_sql:
                # Convert to PostgreSQL
                postgres_create = convert_sqlite_to_postgres(create_sql)
                f.write(postgres_create + ';\n\n')

            # Get indexes
            cursor.execute(f"SELECT sql FROM sqlite_master WHERE type='index' AND tbl_name='{table_name}' AND sql IS NOT NULL;")
            indexes = cursor.fetchall()
            for index in indexes:
                index_sql = index[0]
                # Convert UNIQUE INDEX to PostgreSQL
                postgres_index = convert_sqlite_to_postgres(index_sql)
                f.write(postgres_index + ';\n\n')

            # Dump data
            cursor.execute(f"SELECT * FROM {table_name}")
            rows = cursor.fetchall()
            if rows:
                # Get column names
                cursor.execute(f"PRAGMA table_info({table_name})")
                columns = cursor.fetchall()
                col_names = [col[1] for col in columns]

                for row in rows:
                    values = []
                    for val in row:
                        if val is None:
                            values.append('NULL')
                        elif isinstance(val, str):
                            escaped_val = val.replace("'", "''")
                            values.append(f"'{escaped_val}'")
                        else:
                            values.append(str(val))
                    insert_stmt = f"INSERT INTO {table_name} ({', '.join(col_names)}) VALUES ({', '.join(values)});"
                    f.write(insert_stmt + '\n')

                f.write('\n')

    conn.close()

if __name__ == "__main__":
    dump_sqlite_to_postgres('db.sqlite3', 'sqlite_structure_postgres.txt')
    print("Dump completed to sqlite_structure_postgres.txt")
