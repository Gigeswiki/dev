import psycopg

uri = "postgres://ua8lsi465lep1:pae65d62d83ecbc842491cdfecfc9a94e4c0662ab49d845bdd3db356e1c202b79@c2lr68lb6hupmq.cluster-czz5s0kz4scl.eu-west-1.rds.amazonaws.com:5432/d8r79075nr820k"

with open('postgresql_dump.sql', 'r') as f:
    sql_content = f.read()

statements = [stmt.strip() for stmt in sql_content.split(';') if stmt.strip()]

conn = psycopg.connect(uri)
cursor = conn.cursor()

for stmt in statements:
    try:
        cursor.execute(stmt)
        print(f"Executed: {stmt[:50]}...")
    except Exception as e:
        print(f"Error executing {stmt[:50]}...: {e}")

conn.commit()
cursor.close()
conn.close()

print("Full import completed.")
