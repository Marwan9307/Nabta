<?php

class Database {
    private static $connections = [];

    private static $databases = [
        'users'       => __DIR__ . '/../../database/users.db',
        'items'       => __DIR__ . '/../../database/items.db',
        'orders'      => __DIR__ . '/../../database/orders.db',
        'community'   => __DIR__ . '/../../database/community.db',
        'swaps'       => __DIR__ . '/../../database/swaps.db',
        'shipping'    => __DIR__ . '/../../database/shipping.db',
        'reports'     => __DIR__ . '/../../database/reports.db',
        'chat'        => __DIR__ . '/../../database/chat.db',
        'analytics'   => __DIR__ . '/../../database/analytics.db',
    ];

    public static function get($name) {
        if (isset(self::$connections[$name])) {
            return self::$connections[$name];       // we already have a connection for this database, return it (singleton pattern)
        }
        if (!isset(self::$databases[$name])) {
            throw new Exception("Unknown database: $name");
        }
        $path = self::$databases[$name];
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $pdo = new PDO("sqlite:$path");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec("PRAGMA journal_mode=WAL");
        $pdo->exec("PRAGMA foreign_keys=ON");
        self::$connections[$name] = $pdo;
        return $pdo;
    }
}
