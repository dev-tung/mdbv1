<?php

class Database
{
    private static ?PDO $pdo = null;

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

            echo "SQL ERROR:\n";
            echo $sql . "\n\n";

            echo "PARAMS:\n";
            var_dump($params);

            die($e->getMessage());
        }
    }

    public static function first(
        string $sql,
        array $params = []
    ): ?array
    {
        $result = self::query(
            $sql,
            $params
        )->fetch();

        return $result ?: null;
    }

    public static function get(
        string $sql,
        array $params = []
    ): array
    {
        return self::query(
            $sql,
            $params
        )->fetchAll();
    }

    public static function insert(
        string $sql,
        array $params = []
    ): int
    {
        self::query(
            $sql,
            $params
        );

        return (int) self::connect()->lastInsertId();
    }

    public static function update(
        string $sql,
        array $params = []
    ): int
    {
        return self::query(
            $sql,
            $params
        )->rowCount();
    }

    public static function delete(
        string $sql,
        array $params = []
    ): int
    {
        return self::query(
            $sql,
            $params
        )->rowCount();
    }

    public static function beginTransaction(): void
    {
        self::connect()->beginTransaction();
    }

    public static function commit(): void
    {
        self::connect()->commit();
    }

    public static function rollback(): void
    {
        self::connect()->rollBack();
    }

    public static function pdo(): PDO
    {
        return self::connect();
    }
}