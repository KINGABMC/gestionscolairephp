<?php
require_once "../config/Database.php";

class ProfesseurClasseRepository {
    public function __construct() {
        Database::connexion();
    }

    public function insert(int $professeurId, int $classeId, int $moduleId): int {
        try {
            $dateCreation = date("Y-m-d H:i:s");
            $sql = "INSERT INTO `professeur_classes` (`professeur_id`, `classe_id`, `module_id`, `dateCreation`) 
                    VALUES ($professeurId, $classeId, $moduleId, '$dateCreation')";
            return Database::getPdo()->exec($sql);
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return 0;
    }

    public function selectByProfesseur(int $professeurId): array {
        try {
            $sql = "SELECT c.*, m.nom as module_nom, m.code as module_code 
                    FROM classes c 
                    JOIN professeur_classes pc ON c.id = pc.classe_id 
                    JOIN modules m ON pc.module_id = m.id 
                    WHERE pc.professeur_id = $professeurId";
            $cursor = Database::getPdo()->query($sql);
            $classes = [];
            while ($row = $cursor->fetch()) {
                $classes[] = $row;
            }
            return $classes;
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return [];
    }
}