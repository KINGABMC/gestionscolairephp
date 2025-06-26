<?php
require_once "../src/repository/DemandeRepository.php";

class DemandeService {
    private DemandeRepository $demandeRepository;

    public function __construct() {
        $this->demandeRepository = new DemandeRepository();
    }

    public function addDemande(Demande $demande): void {
        $this->demandeRepository->insert($demande);
    }

    public function getDemandes(string $matricule = "", string $etat = ""): array {
        return $this->demandeRepository->selectAll($matricule, $etat);
    }

    public function getDemandesEtudiant(int $etudiantId, string $etat = ""): array {
        return $this->demandeRepository->selectByEtudiant($etudiantId, $etat);
    }

    public function traiterDemande(int $demandeId, string $decision, int $traitePar): void {
        $this->demandeRepository->traiterDemande($demandeId, $decision, $traitePar);
    }

    public function getDemandeById(int $id): Demande|null {
        return $this->demandeRepository->selectById($id);
    }
}