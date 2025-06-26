<?php
require_once __DIR__.'/../src/models/User.php';
require_once __DIR__.'/../src/repository/UserRepository.php';

session_start();

$controller = $_REQUEST["controller"] ?? 'auth';
if ($controller !== 'auth' && !isset($_SESSION['user'])) {
    header("location:index.php?controller=auth&action=login-form");
    exit;
}

switch ($controller) {
    case 'auth':
        require_once "../src/controllers/AuthController.php";
        $controllerInstance = new AuthController();
        break;
    case 'dashboard':
        require_once "../src/controllers/DashboardController.php";
        $controllerInstance = new DashboardController();
        break;
    case 'classe':
        require_once "../src/controllers/ClasseController.php";
        $controllerInstance = new ClasseController();
        break;
    case 'etudiant':
        require_once "../src/controllers/EtudiantController.php";
        $controllerInstance = new EtudiantController();
        break;
    case 'inscription':
        require_once "../src/controllers/InscriptionController.php";
        $controllerInstance = new InscriptionController();
        break;
    case 'demande':
        require_once "../src/controllers/DemandeController.php";
        $controllerInstance = new DemandeController();
        break;
    case 'professeur':
        require_once "../src/controllers/ProfesseurController.php";
        $controllerInstance = new ProfesseurController();
        break;
    case 'module':
        require_once "../src/controllers/ModuleController.php";
        $controllerInstance = new ModuleController();
        break;
    default:
        require_once "../src/controllers/DashboardController.php";
        $controllerInstance = new DashboardController();
        break;
}
?>