<?php

class Database
{
    private static ?PDO $pdo = null;

    // =========================
    // CONNECT
    // =========================
    private static function connect(): PDO
    {
        if (self::$pdo === null) {

            $host     = Env::get('DB_HOST');
            $dbname   = Env::get('DB_NAME');
            $username = Env::get('DB_USER');
            $password = Env::get('DB_PASS');

            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

            self::$pdo = new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        }

        return self::$pdo;
    }

    // =========================
    // QUERY (WITH DEBUG)
    // =========================
    public static function query(string $sql, array $params = []): PDOStatement
    {
        if (!is_array($params)) {
            $params = [];
        }

        try {
            $stmt = self::connect()->prepare($sql);
            $stmt->execute($params);
            return $stmt;

        } catch (PDOException $e) {

            echo "================ SQL ERROR ================\n";
            echo $sql . "\n\n";

            echo "================ PARAMS ================\n";
            var_dump($params);

            echo "================ ERROR ================\n";
            die($e->getMessage());
        }
    }

    // =========================
    // GET MANY
    // =========================
    public static function get(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    // =========================
    // GET ONE
    // =========================
    public static function first(string $sql, array $params = []): ?array
    {
        $row = self::query($sql, $params)->fetch();
        return $row ?: null;
    }

    // =========================
    // CREATE
    // =========================
    public static function create(string $table, array $data): int
    {
        if (empty($data)) {
            throw new InvalidArgumentException("Data cannot be empty");
        }

        $fields = array_keys($data);

        $columns = implode(', ', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "INSERT INTO {$table} ({$columns})
                VALUES ({$placeholders})";

        self::query($sql, $data);

        return (int) self::connect()->lastInsertId();
    }

    // =========================
    // UPDATE BY ID
    // =========================
    public static function updateById(string $table, int $id, array $data): int
    {
        if (empty($data)) {
            return 0;
        }

        $set = [];

        foreach ($data as $field => $value) {
            $set[] = "{$field} = :{$field}";
        }

        $data['id'] = $id;

        $sql = "UPDATE {$table}
                SET " . implode(', ', $set) . "
                WHERE id = :id";

        return self::query($sql, $data)->rowCount();
    }

    // =========================
    // DELETE BY ID
    // =========================
    public static function deleteById(string $table, int $id): int
    {
        return self::query(
            "DELETE FROM {$table} WHERE id = :id",
            ['id' => $id]
        )->rowCount();
    }

    // =========================
    // PDO
    // =========================
    public static function pdo(): PDO
    {
        return self::connect();
    }

    // =========================
    // TRANSACTION
    // =========================
    public static function transaction(callable $fn)
    {
        self::connect()->beginTransaction();

        try {
            $result = $fn();
            self::connect()->commit();
            return $result;

        } catch (Throwable $e) {
            self::connect()->rollBack();
            throw $e;
        }
    }
}