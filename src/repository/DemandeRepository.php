<?php
require_once "../src/models/Demande.php";
require_once "../config/Database.php";

class DemandeRepository {
    public function __construct() {
        Database::connexion();
    }

    public function insert(Demande $demande): int {
        try {
            $etudiantId = $demande->getEtudiantId();
            $type = $demande->getType();
            $motif = $demande->getMotif();
            $etat = $demande->getEtat();
            $dateDemande = $demande->getDateDemande()->format("Y-m-d H:i:s");
            
            $sql = "INSERT INTO `demandes` (`etudiant_id`, `type`, `motif`, `etat`, `date_demande`) 
                    VALUES ($etudiantId, '$type', '$motif', '$etat', '$dateDemande')";
            return Database::getPdo()->exec($sql);
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return 0;
    }

    public function selectAll(string $matricule = "", string $etat = ""): array {
        try {
            $where = "WHERE 1=1";
            if($matricule != "") {
                $where .= " AND e.matricule LIKE '%$matricule%'";
            }
            if($etat != "") {
                $where .= " AND d.etat = '$etat'";
            }
            
            $sql = "SELECT d.*, e.matricule, e.nom, e.prenom, u.nom as rp_nom, u.prenom as rp_prenom
                    FROM demandes d 
                    JOIN users e ON d.etudiant_id = e.id 
                    LEFT JOIN users u ON d.traite_par = u.id 
                    $where ORDER BY d.date_demande DESC";
            $cursor = Database::getPdo()->query($sql);
            $demandes = [];
            while ($row = $cursor->fetch()) {
                $demandes[] = $row;
            }
            return $demandes;
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return [];
    }

    public function selectByEtudiant(int $etudiantId, string $etat = ""): array {
        try {
            $where = "WHERE etudiant_id = $etudiantId";
            if($etat != "") {
                $where .= " AND etat = '$etat'";
            }
            
            $sql = "SELECT * FROM demandes $where ORDER BY date_demande DESC";
            $cursor = Database::getPdo()->query($sql);
            $demandes = [];
            while ($row = $cursor->fetch()) {
                $demandes[] = Demande::of($row);
            }
            return $demandes;
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return [];
    }

    public function selectById(int $id): Demande|null {
        try {
            $sql = "SELECT * FROM demandes WHERE id = $id";
            $cursor = Database::getPdo()->query($sql);
            if($row = $cursor->fetch()) {
                return Demande::of($row);
            }
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return null;
    }

    public function traiterDemande(int $demandeId, string $decision, int $traitePar): int {
        try {
            $dateTraitement = date("Y-m-d H:i:s");
            $sql = "UPDATE demandes SET etat = '$decision', date_traitement = '$dateTraitement', traite_par = $traitePar WHERE id = $demandeId";
            return Database::getPdo()->exec($sql);
        } catch (\PDOException $ex) {
            print $ex->getMessage()."\n";
        }
        return 0;
    }
}