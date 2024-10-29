<?php

namespace Vlad\FishChat\core;

use PDO;

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
            self::$connection = new PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}", $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$connection;
    }
}