<?php
require_once "../src/models/Inscription.php";
require_once "../config/Database.php";

class InscriptionRepository {
    public function __construct() {
        Database::connexion();
    }

    public function insert(Inscription $inscription): int {
        try {
            $etudiantId = $inscription->getEtudiantId();
            $classeId = $inscription->getClasseId();
            $anneeScolaire = $inscription->getAnneeScolaire();
            $statut = $inscription->getStatut();
            $dateInscription = $inscription->getDateInscription()->format("Y-m-d H:i:s");
            
            $sql = "INSERT INTO `inscriptions` (`etudiant_id`, `classe_id`, `annee_scolaire`, `statut`, `date_inscription`) 
                    VALUES ($etudiantId, $classeId, '$anneeScolaire', '$statut', '$dateInscription')";
            return Database::getPdo()->exec($sql);
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return 0;
    }

    public function selectByClasseAndAnnee(int $classeId, string $anneeScolaire): array {
        try {
            $sql = "SELECT i.*, e.matricule, e.nom, e.prenom, e.adresse, e.sexe 
                    FROM inscriptions i 
                    JOIN etudiants e ON i.etudiant_id = e.id 
                    WHERE i.classe_id = $classeId AND i.annee_scolaire = '$anneeScolaire' 
                    AND i.statut = 'ACTIVE'";
            $cursor = Database::getPdo()->query($sql);
            $inscriptions = [];
            while ($row = $cursor->fetch()) {
                $inscriptions[] = $row;
            }
            return $inscriptions;
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return [];
    }

    public function checkExistingInscription(int $etudiantId, string $anneeScolaire): bool {
        try {
            $sql = "SELECT COUNT(*) as count FROM inscriptions 
                    WHERE etudiant_id = $etudiantId AND annee_scolaire = '$anneeScolaire'";
            $cursor = Database::getPdo()->query($sql);
            if($row = $cursor->fetch()) {
                return $row['count'] > 0;
            }
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return false;
    }

    public function getStatistiques(): array {
        try {
            // Effectif par année
            $sql1 = "SELECT annee_scolaire, COUNT(*) as effectif 
                     FROM inscriptions WHERE statut = 'ACTIVE' 
                     GROUP BY annee_scolaire";
            $cursor1 = Database::getPdo()->query($sql1);
            $effectifParAnnee = [];
            while ($row = $cursor1->fetch()) {
                $effectifParAnnee[] = $row;
            }

            // Répartition par sexe et année
            $sql2 = "SELECT i.annee_scolaire, e.sexe, COUNT(*) as nombre 
                     FROM inscriptions i 
                     JOIN etudiants e ON i.etudiant_id = e.id 
                     WHERE i.statut = 'ACTIVE' 
                     GROUP BY i.annee_scolaire, e.sexe";
            $cursor2 = Database::getPdo()->query($sql2);
            $repartitionSexeAnnee = [];
            while ($row = $cursor2->fetch()) {
                $repartitionSexeAnnee[] = $row;
            }

            // Effectif par classe
            $sql3 = "SELECT c.libelle, c.filiere, COUNT(*) as effectif 
                     FROM inscriptions i 
                     JOIN classes c ON i.classe_id = c.id 
                     WHERE i.statut = 'ACTIVE' AND i.annee_scolaire = '2024-2025'
                     GROUP BY c.id, c.libelle, c.filiere";
            $cursor3 = Database::getPdo()->query($sql3);
            $effectifParClasse = [];
            while ($row = $cursor3->fetch()) {
                $effectifParClasse[] = $row;
            }

            // Répartition par sexe et classe
            $sql4 = "SELECT c.libelle, e.sexe, COUNT(*) as nombre 
                     FROM inscriptions i 
                     JOIN classes c ON i.classe_id = c.id 
                     JOIN etudiants e ON i.etudiant_id = e.id 
                     WHERE i.statut = 'ACTIVE' AND i.annee_scolaire = '2024-2025'
                     GROUP BY c.id, c.libelle, e.sexe";
            $cursor4 = Database::getPdo()->query($sql4);
            $repartitionSexeClasse = [];
            while ($row = $cursor4->fetch()) {
                $repartitionSexeClasse[] = $row;
            }

            // Suspensions et annulations par année
            $sql5 = "SELECT i.annee_scolaire,
                            SUM(CASE WHEN i.statut = 'SUSPENDUE' THEN 1 ELSE 0 END) as suspensions,
                            SUM(CASE WHEN i.statut = 'ANNULEE' THEN 1 ELSE 0 END) as annulations
                     FROM inscriptions i 
                     GROUP BY i.annee_scolaire";
            $cursor5 = Database::getPdo()->query($sql5);
            $suspensionsAnnulations = [];
            while ($row = $cursor5->fetch()) {
                $suspensionsAnnulations[] = $row;
            }

            return [
                'effectifParAnnee' => $effectifParAnnee,
                'repartitionSexeAnnee' => $repartitionSexeAnnee,
                'effectifParClasse' => $effectifParClasse,
                'repartitionSexeClasse' => $repartitionSexeClasse,
                'suspensionsAnnulations' => $suspensionsAnnulations,
                'totalEtudiants' => count($effectifParAnnee) > 0 ? array_sum(array_column($effectifParAnnee, 'effectif')) : 0,
                'totalClasses' => count($effectifParClasse),
                'totalProfesseurs' => $this->countProfesseurs(),
                'totalModules' => $this->countModules()
            ];
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return [];
    }

    private function countProfesseurs(): int {
        try {
            $sql = "SELECT COUNT(*) as count FROM users WHERE role = 'PROFESSEUR'";
            $cursor = Database::getPdo()->query($sql);
            if($row = $cursor->fetch()) {
                return $row['count'];
            }
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return 0;
    }

    private function countModules(): int {
        try {
            $sql = "SELECT COUNT(*) as count FROM modules";
            $cursor = Database::getPdo()->query($sql);
            if($row = $cursor->fetch()) {
                return $row['count'];
            }
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return 0;
    }
}