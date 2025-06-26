<?php
require_once "../src/repository/EtudiantRepository.php";
require_once "../src/repository/UserRepository.php";
require_once "../src/models/User.php";

class EtudiantService {
    private EtudiantRepository $etudiantRepository;
    private UserRepository $userRepository;

    public function __construct() {
        $this->etudiantRepository = new EtudiantRepository();
        $this->userRepository = new UserRepository();
    }

    public function addEtudiant(Etudiant $etudiant): void {
        $this->etudiantRepository->insert($etudiant);
        
        // Créer automatiquement un compte utilisateur pour l'étudiant
        $this->createUserAccountForStudent($etudiant);
    }

    public function getEtudiants(string $nom = ""): array {
        return $this->etudiantRepository->selectAll($nom);
    }

    public function getEtudiantByMatricule(string $matricule): Etudiant|null {
        return $this->etudiantRepository->selectByMatricule($matricule);
    }

    public function getEtudiantById(int $id): Etudiant|null {
        return $this->etudiantRepository->selectById($id);
    }

    /**
     * Crée automatiquement un compte utilisateur pour un étudiant
     */
    private function createUserAccountForStudent(Etudiant $etudiant): void {
        try {
            // Vérifier si l'étudiant a déjà un compte
            $existingUser = $this->userRepository->findByEtudiantId($etudiant->getId());
            if ($existingUser) {
                return; // Compte déjà existant
            }

            // Générer l'email basé sur le matricule
            $email = strtolower($etudiant->getMatricule()) . "@ism.sn";
            
            // Vérifier si l'email existe déjà
            $existingEmailUser = $this->userRepository->findByEmail($email);
            if ($existingEmailUser) {
                return; // Email déjà utilisé
            }

            // Créer le compte utilisateur
            $user = new User(
                $etudiant->getNom(),
                $etudiant->getPrenom(),
                $email,
                'etudiant123', // Mot de passe en clair
                'ETUDIANT'
            );
            $user->setEtudiantId($etudiant->getId());

            $this->userRepository->insert($user);
        } catch (Exception $e) {
            // Log l'erreur mais ne pas interrompre le processus
            error_log("Erreur création compte étudiant: " . $e->getMessage());
        }
    }

    /**
     * Crée des comptes pour tous les étudiants existants qui n'en ont pas
     */
    public function createAccountsForExistingStudents(): array {
        $results = [
            'created' => 0,
            'skipped' => 0,
            'errors' => []
        ];

        try {
            // Récupérer tous les étudiants
            $etudiants = $this->etudiantRepository->selectAll();

            foreach ($etudiants as $etudiant) {
                try {
                    // Vérifier si l'étudiant a déjà un compte
                    $existingUser = $this->userRepository->findByEtudiantId($etudiant->getId());
                    if ($existingUser) {
                        $results['skipped']++;
                        continue;
                    }

                    // Générer l'email basé sur le matricule
                    $email = strtolower($etudiant->getMatricule()) . "@ism.sn";
                    
                    // Vérifier si l'email existe déjà
                    $existingEmailUser = $this->userRepository->findByEmail($email);
                    if ($existingEmailUser) {
                        $results['errors'][] = "Email {$email} déjà utilisé pour {$etudiant->getNomComplet()}";
                        continue;
                    }

                    // Créer le compte utilisateur
                    $user = new User(
                        $etudiant->getNom(),
                        $etudiant->getPrenom(),
                        $email,
                        'etudiant123', // Mot de passe en clair
                        'ETUDIANT'
                    );
                    $user->setEtudiantId($etudiant->getId());

                    $this->userRepository->insert($user);
                    $results['created']++;

                } catch (Exception $e) {
                    $results['errors'][] = "Erreur pour {$etudiant->getNomComplet()}: " . $e->getMessage();
                }
            }

        } catch (Exception $e) {
            $results['errors'][] = "Erreur générale: " . $e->getMessage();
        }

        return $results;
    }
}