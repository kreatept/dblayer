<?php

namespace Kreatept\DBLayer;

use PDO;
use Exception;

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (!self::$instance) {
            try {
                $config = DBLAYER_CONFIG;
                $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
                self::$instance = new PDO($dsn, $config['username'], $config['passwd'], $config['options']);
            } catch (Exception $e) {
                die("Database connection error: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
