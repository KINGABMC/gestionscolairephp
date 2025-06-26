<?php
require_once "../src/services/DemandeService.php";
require_once "../src/services/AuthService.php";
require_once "../src/models/Demande.php";

class DemandeController {
    private DemandeService $demandeService;
    private AuthService $authService;

    public function __construct() {
        $this->demandeService = new DemandeService();
        $this->authService = new AuthService();
        $this->handleRequest();
    }

    private function handleRequest() {
        $action = $_REQUEST["action"] ?? 'list-demandes';
        
        switch ($action) {
            case 'form-demande':
                $this->showFormDemande();
                break;
            case 'list-demandes':
                $this->showListDemandes();
                break;
            case 'save-demande':
                $this->saveDemande();
                break;
            case 'traiter-demande':
                $this->traiterDemande();
                break;
            case 'mes-demandes':
                $this->showMesDemandes();
                break;
            default:
                $this->showListDemandes();
                break;
        }
    }

    public function showFormDemande() {
        if (!$this->authService->hasRole('ETUDIANT')) {
            header("location:index.php?controller=dashboard");
            return;
        }

        require_once "../views/layout/header.html.php";
        require_once "../views/demande/form.html.php";
        require_once "../views/layout/footer.html.php";
    }

    public function showListDemandes() {
        if (!$this->authService->hasAnyRole(['RP', 'ATTACHE'])) {
            header("location:index.php?controller=dashboard");
            return;
        }

        $matricule = $_REQUEST["matricule"] ?? "";
        $etat = $_REQUEST["etat"] ?? "";
        $demandes = $this->demandeService->getDemandes($matricule, $etat);
        
        require_once "../views/layout/header.html.php";
        require_once "../views/demande/liste.html.php";
        require_once "../views/layout/footer.html.php";
    }

    public function showMesDemandes() {
        if (!$this->authService->hasRole('ETUDIANT')) {
            header("location:index.php?controller=dashboard");
            return;
        }

        $user = $this->authService->getCurrentUser();
        $etat = $_REQUEST["etat"] ?? "";
        $demandes = $this->demandeService->getDemandesEtudiant($user->getId(), $etat);
        
        require_once "../views/layout/header.html.php";
        require_once "../views/demande/mes-demandes.html.php";
        require_once "../views/layout/footer.html.php";
    }

    public function saveDemande() {
        if (!$this->authService->hasRole('ETUDIANT')) {
            header("location:index.php?controller=dashboard");
            return;
        }

        $type = $_REQUEST["type"];
        $motif = $_REQUEST["motif"];
        $user = $this->authService->getCurrentUser();
        
        $demande = new Demande($user->getId(), $type, $motif);
        $this->demandeService->addDemande($demande);
        
        header("location:index.php?controller=demande&action=mes-demandes");
    }

    public function traiterDemande() {
        if (!$this->authService->hasRole('RP')) {
            header("location:index.php?controller=dashboard");
            return;
        }

        $demandeId = $_REQUEST["demande_id"];
        $decision = $_REQUEST["decision"]; // ACCEPTEE ou REFUSEE
        $user = $this->authService->getCurrentUser();
        
        $this->demandeService->traiterDemande($demandeId, $decision, $user->getId());
        
        header("location:index.php?controller=demande&action=list-demandes");
    }
}