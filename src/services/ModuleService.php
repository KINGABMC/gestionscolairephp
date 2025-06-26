<?php
require_once "../src/repository/ModuleRepository.php";

class ModuleService {
    private ModuleRepository $moduleRepository;

    public function __construct() {
        $this->moduleRepository = new ModuleRepository();
    }

    public function addModule(Module $module): void {
        $this->moduleRepository->insert($module);
    }

    public function getModules(string $nom = ""): array {
        return $this->moduleRepository->selectAll($nom);
    }

    public function getModuleById(int $id): Module|null {
        return $this->moduleRepository->selectById($id);
    }
}