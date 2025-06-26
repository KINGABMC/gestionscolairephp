<?php
require_once "../src/services/ProfesseurService.php";
require_once "../src/services/AuthService.php";
require_once "../src/models/User.php";

class ProfesseurController {
    private ProfesseurService $professeurService;
    private AuthService $authService;

    public function __construct() {
        $this->professeurService = new ProfesseurService();
        $this->authService = new AuthService();
        $this->handleRequest();
    }

    private function handleRequest() {
        $action = $_REQUEST["action"] ?? 'list-professeurs';
        
        switch ($action) {
            case 'form-professeur':
                $this->showFormProfesseur();
                break;
            case 'list-professeurs':
                $this->showListProfesseurs();
                break;
            case 'save-professeur':
                $this->saveProfesseur();
                break;
            case 'affecter-classe':
                $this->affecterClasse();
                break;
            case 'mes-classes':
                $this->showMesClasses();
                break;
            default:
                $this->showListProfesseurs();
                break;
        }
    }

    public function showFormProfesseur() {
        if (!$this->authService->hasRole('RP')) {
            header("location:index.php?controller=dashboard");
            return;
        }

        $modules = $this->professeurService->getModules();
        
        require_once "../views/layout/header.html.php";
        require_once "../views/professeur/form.html.php";
        require_once "../views/layout/footer.html.php";
    }

    public function showListProfesseurs() {
        if (!$this->authService->hasRole('RP')) {
            header("location:index.php?controller=dashboard");
            return;
        }

        $nom = $_REQUEST["nom"] ?? "";
        $professeurs = $this->professeurService->getProfesseurs($nom);
        
        require_once "../views/layout/header.html.php";
        require_once "../views/professeur/liste.html.php";
        require_once "../views/layout/footer.html.php";
    }

    public function showMesClasses() {
        if (!$this->authService->hasRole('PROFESSEUR')) {
            header("location:index.php?controller=dashboard");
            return;
        }

        $user = $this->authService->getCurrentUser();
        $classes = $this->professeurService->getClassesProfesseur($user->getId());
        
        require_once "../views/layout/header.html.php";
        require_once "../views/professeur/mes-classes.html.php";
        require_once "../views/layout/footer.html.php";
    }

    public function saveProfesseur() {
        if (!$this->authService->hasRole('RP')) {
            header("location:index.php?controller=dashboard");
            return;
        }

        $nom = $_REQUEST["nom"];
        $prenom = $_REQUEST["prenom"];
        $email = $_REQUEST["email"];
        $password = $_REQUEST["password"];
        $grade = $_REQUEST["grade"];
        $modules = $_REQUEST["modules"] ?? [];
        
        $professeur = new User($nom, $prenom, $email, $password, 'PROFESSEUR');
        $professeur->setGrade($grade);
        
        $professeurId = $this->professeurService->addProfesseur($professeur, $modules);
        
        header("location:index.php?controller=professeur&action=list-professeurs");
    }

    public function affecterClasse() {
        if (!$this->authService->hasRole('RP')) {
            header("location:index.php?controller=dashboard");
            return;
        }

        $professeurId = $_REQUEST["professeur_id"];
        $classeId = $_REQUEST["classe_id"];
        $moduleId = $_REQUEST["module_id"];
        
        $this->professeurService->affecterClasse($professeurId, $classeId, $moduleId);
        
        header("location:index.php?controller=professeur&action=list-professeurs");
    }
}