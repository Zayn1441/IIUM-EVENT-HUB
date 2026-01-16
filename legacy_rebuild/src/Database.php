<?php

class Database
{
    private static $pdo;

    public static function connect()
    {
        if (!isset(self::$pdo)) {
            $config = require __DIR__ . '/../config/database.php';

            try {
                $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                self::$pdo = new PDO($dsn, $config['username'], $config['password'], $options);
            } catch (PDOException $e) {
                // For API, return JSON error
                if (defined('API_REQUEST')) {
                    header('Content-Type: application/json');
                    http_response_code(500);
                    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
                    exit;
                }
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
