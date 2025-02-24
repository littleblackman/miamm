<?php

namespace App\Core;

use PDO;
use PDOException;
use App\Core\Config;

class EntityManager {
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            try {
                $dsn = 'mysql:host=' . Config::get('DB_HOST') . ';dbname=' . Config::get('DB_NAME') . ';charset=utf8';
                self::$pdo = new PDO($dsn, Config::get('DB_USER'), Config::get('DB_PASSWORD'), [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }

        return self::$pdo;
    }

    public function query(string $query, array $params = []): array
    {
        $stmt = self::$pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
