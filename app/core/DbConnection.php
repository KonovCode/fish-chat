<?php

namespace Vlad\FishChat\core;

use PDO;
use PDOException;

class DbConnection
{
    private static ?PDO $connection = null;

    private function __construct() {}
    private function __clone() {}
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize.");
    }

    public static function getConnection(): ?PDO
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}",
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASSWORD']
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log($e->getMessage());
                throw new \Exception("Database connection error.");
            }
        }

        return self::$connection;
    }

    public static function fetchAll(string $query, array $params = []): array
    {
        $stmt = self::getConnection()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function execute(string $query, array $params = []): bool
    {
        $stmt = self::getConnection()->prepare($query);
        return $stmt->execute($params);
    }

    public static function fetchOne(string $query, array $params = []): ?array
    {
        $stmt = self::getConnection()->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch();

        return $result ?: null;
    }
}
