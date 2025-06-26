<?php
require_once "../config/Database.php";

class ProfesseurModuleRepository {
    public function __construct() {
        Database::connexion();
    }

    public function insert(int $professeurId, int $moduleId): int {
        try {
            $dateCreation = date("Y-m-d H:i:s");
            $sql = "INSERT INTO `professeur_modules` (`professeur_id`, `module_id`, `dateCreation`) 
                    VALUES ($professeurId, $moduleId, '$dateCreation')";
            return Database::getPdo()->exec($sql);
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return 0;
    }

    public function selectByProfesseur(int $professeurId): array {
        try {
            $sql = "SELECT m.* FROM modules m 
                    JOIN professeur_modules pm ON m.id = pm.module_id 
                    WHERE pm.professeur_id = $professeurId";
            $cursor = Database::getPdo()->query($sql);
            $modules = [];
            while ($row = $cursor->fetch()) {
                $modules[] = Module::of($row);
            }
            return $modules;
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return [];
    }

    public function selectByModule(int $moduleId): array {
        try {
            $sql = "SELECT u.* FROM users u 
                    JOIN professeur_modules pm ON u.id = pm.professeur_id 
                    WHERE pm.module_id = $moduleId AND u.role = 'PROFESSEUR'";
            $cursor = Database::getPdo()->query($sql);
            $professeurs = [];
            while ($row = $cursor->fetch()) {
                $professeurs[] = User::of($row);
            }
            return $professeurs;
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return [];
    }
}