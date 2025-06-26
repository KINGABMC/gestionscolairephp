<?php
require_once "../src/services/EtudiantService.php";
require_once "../src/services/AuthService.php";
require_once "../src/models/Etudiant.php";

class EtudiantController {
    private EtudiantService $etudiantService;
    private AuthService $authService;

    public function __construct() {
        $this->etudiantService = new EtudiantService();
        $this->authService = new AuthService();
        $this->handleRequest();
    }

    private function handleRequest() {
        $action = $_REQUEST["action"] ?? 'list-etudiant';
        
        switch ($action) {
            case 'form-etudiant':
                $this->showFormEtudiant();
                break;
            case 'list-etudiant':
                $this->showListEtudiant();
                break;
            case 'save-etudiant':
                $this->saveEtudiant();
                break;
            case 'create-accounts':
                $this->createStudentAccounts();
                break;
            default:
                $this->showListEtudiant();
                break;
        }
    }

    public function showListEtudiant() {
        $nom = $_REQUEST["nom"] ?? "";
        $etudiants = $this->etudiantService->getEtudiants($nom);
        
        require_once "../views/layout/header.html.php";
        require_once "../views/etudiant/liste.html.php";
        require_once "../views/layout/footer.html.php";
    }

    public function showFormEtudiant() {
        require_once "../views/layout/header.html.php";
        require_once "../views/etudiant/form.html.php";
        require_once "../views/layout/footer.html.php";
    }

    public function saveEtudiant() {
        $nom = $_REQUEST["nom"];
        $prenom = $_REQUEST["prenom"];
        $adresse = $_REQUEST["adresse"];
        $sexe = $_REQUEST["sexe"];
        
        $etudiant = new Etudiant($nom, $prenom, $adresse, $sexe);
        $this->etudiantService->addEtudiant($etudiant);
        
        header("location:index.php?controller=etudiant&action=list-etudiant");
    }

    public function createStudentAccounts() {
        // Vérifier les permissions (seul RP peut faire ça)
        if (!$this->authService->hasRole('RP')) {
            header("location:index.php?controller=dashboard");
            return;
        }

        $results = $this->etudiantService->createAccountsForExistingStudents();
        
        require_once "../views/layout/header.html.php";
        require_once "../views/etudiant/create-accounts-result.html.php";
        require_once "../views/layout/footer.html.php";
    }
}