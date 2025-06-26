<?php 
class Database {
    private static ?PDO $pdo = null;

    public static function connexion(): void {
        try {
            $server = '127.0.0.1'; 
            $dbname = 'gestscolaire';
            $username = 'root';
            $password = ''; 
            $charset = 'utf8mb4';
            $port = 3306;

            $chaineConnexion = "mysql:host=$server;port=$port;dbname=$dbname;charset=$charset";

            if (self::$pdo === null) {
                self::$pdo = new PDO($chaineConnexion, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            }
        } catch (\PDOException $ex) {
            echo "Erreur de connexion à la base : " . $ex->getMessage();
        }
    }

    public static function getPdo(): PDO {
        return self::$pdo;
    }
}