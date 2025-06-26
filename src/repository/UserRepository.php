<?php
require_once "../src/models/User.php";
require_once "../config/Database.php";

class UserRepository {
    public function __construct() {
        Database::connexion();
    }

    public function insert(User $user): int {
        try {
            $nom = $user->getNom();
            $prenom = $user->getPrenom();
            $email = $user->getEmail();
            $password = $user->getPassword();
            $role = $user->getRole();
            $grade = $user->getGrade();
            $dateCreation = $user->getDateCreation()->format("Y-m-d H:i:s");
            
            $sql = "INSERT INTO `users` (`nom`, `prenom`, `email`, `password`, `role`, `grade`, `dateCreation`) 
                    VALUES ('$nom', '$prenom', '$email', '$password', '$role', '$grade', '$dateCreation')";
            Database::getPdo()->exec($sql);
            return Database::getPdo()->lastInsertId();
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return 0;
    }

    public function findByEmail(string $email): User|null {
        try {
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $cursor = Database::getPdo()->query($sql);
            if($row = $cursor->fetch()) {
                return User::of($row);
            }
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return null;
    }

    public function selectByRole(string $role, string $nom = ""): array {
        try {
            $where = "WHERE role = '$role'";
            if($nom != "") {
                $where .= " AND (nom LIKE '%$nom%' OR prenom LIKE '%$nom%')";
            }
            $sql = "SELECT * FROM users $where ORDER BY nom, prenom";
            $cursor = Database::getPdo()->query($sql);
            $users = [];
            while ($row = $cursor->fetch()) {
                $users[] = User::of($row);
            }
            return $users;
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return [];
    }

    public function selectById(int $id): User|null {
        try {
            $sql = "SELECT * FROM users WHERE id = $id";
            $cursor = Database::getPdo()->query($sql);
            if($row = $cursor->fetch()) {
                return User::of($row);
            }
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return null;
    }
}