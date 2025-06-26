<?php
require_once "../src/models/Module.php";
require_once "../config/Database.php";

class ModuleRepository {
    public function __construct() {
        Database::connexion();
    }

    public function insert(Module $module): int {
        try {
            $nom = $module->getNom();
            $code = $module->getCode();
            $coefficient = $module->getCoefficient();
            $dateCreation = $module->getDateCreation()->format("Y-m-d H:i:s");
            
            $sql = "INSERT INTO `modules` (`nom`, `code`, `coefficient`, `dateCreation`) 
                    VALUES ('$nom', '$code', $coefficient, '$dateCreation')";
            return Database::getPdo()->exec($sql);
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return 0;
    }

    public function selectAll(string $nom = ""): array {
        try {
            $where = "";
            if($nom != "") {
                $where = "WHERE nom LIKE '%$nom%' OR code LIKE '%$nom%'";
            }
            $sql = "SELECT * FROM modules $where ORDER BY nom";
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

    public function selectById(int $id): Module|null {
        try {
            $sql = "SELECT * FROM modules WHERE id = $id";
            $cursor = Database::getPdo()->query($sql);
            if($row = $cursor->fetch()) {
                return Module::of($row);
            }
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return null;
    }
}