<?php
namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;
use Throwable;
use Exception;

class Database
{
	private static ?PDO $pdo = null;

	// =========================
	// CONNECT
	// =========================
	private static function connect(): PDO
	{
		if (self::$pdo === null) {
			$host = Env::get('DB_HOST');
			$dbname = Env::get('DB_NAME');
			$username = Env::get('DB_USER');
			$password = Env::get('DB_PASS');

			$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

			self::$pdo = new PDO($dsn, $username, $password, [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES => false,
			]);
		}

		return self::$pdo;
	}

	// =========================
	// RAW SQL (Migration / Procedure)
	// =========================
	public static function raw(string $sql): void
	{
		try {
			self::connect()->exec($sql);
		} catch (PDOException $e) {
			throw new Exception($e->errorInfo[2] ?? $e->getMessage(), (int) ($e->errorInfo[1] ?? 0), $e);
		}
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
			throw new Exception($e->errorInfo[2] ?? $e->getMessage(), (int) ($e->errorInfo[1] ?? 0), $e);
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
	// CALL PROCEDURE
	// =========================
	public static function call(string $sql, array $params = []): array
	{
		$stmt = self::query($sql, $params);

		$results = [];

		do {
			$results[] = $stmt->fetchAll();
		} while ($stmt->nextRowset());

		$stmt->closeCursor();

		return $results;
	}

	// =========================
	// EXECUTE
	// =========================
	public static function execute(string $sql, array $params = []): int
	{
		return self::query($sql, $params)->rowCount();
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

	// =========================
	// LAST INSERT ID
	// =========================
	public static function lastInsertId(): int
	{
		return (int) self::connect()->lastInsertId();
	}
}
