<?php
declare(strict_types=1);

namespace App\Core;
use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function getConnection(array $config): PDO
    {
        if (self::$pdo === null) {
            $dbConfig = $config['db'];

            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $dbConfig['host'],
                $dbConfig['port'],
                $dbConfig['name'],
                $dbConfig['charset']
            );
            try {
                self::$pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['password']);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                self::$pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
            } catch (PDOException $e) {
                throw new PDOException('Database connection failed: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}