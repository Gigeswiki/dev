import sqlite3

def dump_sqlite_data_only(db_path, output_file):
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()

    with open(output_file, 'w') as f:
        # Get all table names
        cursor.execute("SELECT name FROM sqlite_master WHERE type='table';")
        tables = cursor.fetchall()

        for table in tables:
            table_name = table[0]
            # Skip sqlite_sequence if exists
            if table_name == 'sqlite_sequence':
                continue

            # Get column names
            cursor.execute(f"PRAGMA table_info({table_name})")
            columns = cursor.fetchall()
            col_names = [col[1] for col in columns]

            # Dump data
            cursor.execute(f"SELECT * FROM {table_name}")
            rows = cursor.fetchall()
            if rows:
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
                    f.write(insert_stmt + "\n")

            f.write("\n")

    conn.close()

if __name__ == "__main__":
    dump_sqlite_data_only('db.sqlite3', 'postgresql_data_only.sql')
