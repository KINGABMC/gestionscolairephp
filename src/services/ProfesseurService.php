<?php
require_once "../src/repository/UserRepository.php";
require_once "../src/repository/ModuleRepository.php";
require_once "../src/repository/ProfesseurModuleRepository.php";
require_once "../src/repository/ProfesseurClasseRepository.php";

class ProfesseurService {
    private UserRepository $userRepository;
    private ModuleRepository $moduleRepository;
    private ProfesseurModuleRepository $professeurModuleRepository;
    private ProfesseurClasseRepository $professeurClasseRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
        $this->moduleRepository = new ModuleRepository();
        $this->professeurModuleRepository = new ProfesseurModuleRepository();
        $this->professeurClasseRepository = new ProfesseurClasseRepository();
    }

    public function addProfesseur(User $professeur, array $modules = []): int {
        $professeurId = $this->userRepository->insert($professeur);
        
        // Associer les modules au professeur
        foreach ($modules as $moduleId) {
            $this->professeurModuleRepository->insert($professeurId, $moduleId);
        }
        
        return $professeurId;
    }

    public function getProfesseurs(string $nom = ""): array {
        return $this->userRepository->selectByRole('PROFESSEUR', $nom);
    }

    public function getModules(string $nom = ""): array {
        return $this->moduleRepository->selectAll($nom);
    }

    public function affecterClasse(int $professeurId, int $classeId, int $moduleId): void {
        $this->professeurClasseRepository->insert($professeurId, $classeId, $moduleId);
    }

    public function getClassesProfesseur(int $professeurId): array {
        return $this->professeurClasseRepository->selectByProfesseur($professeurId);
    }

    public function getModulesProfesseur(int $professeurId): array {
        return $this->professeurModuleRepository->selectByProfesseur($professeurId);
    }

    public function getProfesseursModule(int $moduleId): array {
        return $this->professeurModuleRepository->selectByModule($moduleId);
    }
}