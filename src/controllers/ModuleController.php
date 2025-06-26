<?php
require_once "../src/services/ModuleService.php";
require_once "../src/services/AuthService.php";
require_once "../src/models/Module.php";

class ModuleController {
    private ModuleService $moduleService;
    private AuthService $authService;

    public function __construct() {
        $this->moduleService = new ModuleService();
        $this->authService = new AuthService();
        $this->handleRequest();
    }

    private function handleRequest() {
        $action = $_REQUEST["action"] ?? 'list-modules';
        
        switch ($action) {
            case 'form-module':
                $this->showFormModule();
                break;
            case 'list-modules':
                $this->showListModules();
                break;
            case 'save-module':
                $this->saveModule();
                break;
            default:
                $this->showListModules();
                break;
        }
    }

    public function showFormModule() {
        if (!$this->authService->hasRole('RP')) {
            header("location:index.php?controller=dashboard");
            return;
        }

        require_once "../views/layout/header.html.php";
        require_once "../views/module/form.html.php";
        require_once "../views/layout/footer.html.php";
    }

    public function showListModules() {
        if (!$this->authService->hasRole('RP')) {
            header("location:index.php?controller=dashboard");
            return;
        }

        $nom = $_REQUEST["nom"] ?? "";
        $modules = $this->moduleService->getModules($nom);
        
        require_once "../views/layout/header.html.php";
        require_once "../views/module/liste.html.php";
        require_once "../views/layout/footer.html.php";
    }

    public function saveModule() {
        if (!$this->authService->hasRole('RP')) {
            header("location:index.php?controller=dashboard");
            return;
        }

        $nom = $_REQUEST["nom"];
        $code = $_REQUEST["code"];
        $coefficient = $_REQUEST["coefficient"];
        
        $module = new Module($nom, $code, $coefficient);
        $this->moduleService->addModule($module);
        
        header("location:index.php?controller=module&action=list-modules");
    }
}