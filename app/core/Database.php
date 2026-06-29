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
    // QUERY
    // =========================
    public static function query(string $sql, array $params = []): PDOStatement
    {
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
    // PDO
    // =========================
    public static function pdo(): PDO
    {
        return self::connect();
    }

    // =========================
    // TRANSACTION
    // =========================
    public static function transaction(callable $callback)
    {
        self::connect()->beginTransaction();

        try {

            $result = $callback();

            self::connect()->commit();

            return $result;

        } catch (Throwable $e) {

            self::connect()->rollBack();

            throw $e;
        }
    }
}