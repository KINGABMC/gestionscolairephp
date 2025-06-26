<?php
/**
 * Script pour créer automatiquement des comptes utilisateurs 
 * pour tous les étudiants existants dans la base de données
 */

require_once "../config/Database.php";

class StudentAccountCreator {
    private PDO $pdo;
    
    public function __construct() {
        Database::connexion();
        $this->pdo = Database::getPdo();
    }
    
    public function createStudentAccounts(): array {
        $results = [
            'created' => 0,
            'skipped' => 0,
            'errors' => [],
            'details' => []
        ];
        
        try {
            // Récupérer tous les étudiants
            $etudiants = $this->getAllStudents();
            
            foreach ($etudiants as $etudiant) {
                $result = $this->createAccountForStudent($etudiant);
                
                if ($result['success']) {
                    $results['created']++;
                    $results['details'][] = "✓ Compte créé pour {$etudiant['prenom']} {$etudiant['nom']} ({$etudiant['matricule']})";
                } else {
                    if ($result['reason'] === 'exists') {
                        $results['skipped']++;
                        $results['details'][] = "- Compte déjà existant pour {$etudiant['prenom']} {$etudiant['nom']} ({$etudiant['matricule']})";
                    } else {
                        $results['errors'][] = "✗ Erreur pour {$etudiant['prenom']} {$etudiant['nom']}: {$result['error']}";
                    }
                }
            }
            
        } catch (Exception $e) {
            $results['errors'][] = "Erreur générale: " . $e->getMessage();
        }
        
        return $results;
    }
    
    private function getAllStudents(): array {
        $sql = "SELECT id, matricule, nom, prenom FROM etudiants ORDER BY nom, prenom";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function createAccountForStudent(array $etudiant): array {
        try {
            // Vérifier si un compte existe déjà pour cet étudiant
            if ($this->studentAccountExists($etudiant['id'])) {
                return ['success' => false, 'reason' => 'exists'];
            }
            
            // Générer l'email basé sur le matricule
            $email = strtolower($etudiant['matricule']) . "@ism.sn";
            
            // Vérifier si l'email existe déjà
            if ($this->emailExists($email)) {
                return ['success' => false, 'reason' => 'email_exists', 'error' => "Email {$email} déjà utilisé"];
            }
            
            // Créer le compte
            $sql = "INSERT INTO users (nom, prenom, email, password, role, etudiant_id, dateCreation) 
                    VALUES (:nom, :prenom, :email, :password, 'ETUDIANT', :etudiant_id, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $success = $stmt->execute([
                'nom' => $etudiant['nom'],
                'prenom' => $etudiant['prenom'],
                'email' => $email,
                'password' => 'etudiant123', // Mot de passe en clair comme demandé
                'etudiant_id' => $etudiant['id']
            ]);
            
            return ['success' => $success];
            
        } catch (PDOException $e) {
            return ['success' => false, 'reason' => 'error', 'error' => $e->getMessage()];
        }
    }
    
    private function studentAccountExists(int $etudiantId): bool {
        $sql = "SELECT COUNT(*) FROM users WHERE etudiant_id = :etudiant_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['etudiant_id' => $etudiantId]);
        return $stmt->fetchColumn() > 0;
    }
    
    private function emailExists(string $email): bool {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
    
    public function getStatistics(): array {
        try {
            // Nombre total d'étudiants
            $totalStudents = $this->pdo->query("SELECT COUNT(*) FROM etudiants")->fetchColumn();
            
            // Nombre d'étudiants avec compte
            $studentsWithAccount = $this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'ETUDIANT'")->fetchColumn();
            
            // Nombre d'étudiants sans compte
            $studentsWithoutAccount = $this->pdo->query("
                SELECT COUNT(*) FROM etudiants e 
                LEFT JOIN users u ON e.id = u.etudiant_id 
                WHERE u.id IS NULL
            ")->fetchColumn();
            
            return [
                'total_students' => $totalStudents,
                'students_with_account' => $studentsWithAccount,
                'students_without_account' => $studentsWithoutAccount
            ];
            
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}

// Exécution du script si appelé directement
if (basename($_SERVER['PHP_SELF']) === 'create_student_accounts.php') {
    $creator = new StudentAccountCreator();
    
    echo "<h2>Création des comptes étudiants</h2>\n";
    
    // Afficher les statistiques avant
    echo "<h3>Statistiques avant traitement :</h3>\n";
    $statsBefore = $creator->getStatistics();
    if (isset($statsBefore['error'])) {
        echo "<p style='color: red;'>Erreur: {$statsBefore['error']}</p>\n";
    } else {
        echo "<ul>\n";
        echo "<li>Total étudiants: {$statsBefore['total_students']}</li>\n";
        echo "<li>Étudiants avec compte: {$statsBefore['students_with_account']}</li>\n";
        echo "<li>Étudiants sans compte: {$statsBefore['students_without_account']}</li>\n";
        echo "</ul>\n";
    }
    
    // Créer les comptes
    echo "<h3>Traitement en cours...</h3>\n";
    $results = $creator->createStudentAccounts();
    
    // Afficher les résultats
    echo "<h3>Résultats :</h3>\n";
    echo "<p><strong>Comptes créés:</strong> {$results['created']}</p>\n";
    echo "<p><strong>Comptes ignorés (déjà existants):</strong> {$results['skipped']}</p>\n";
    echo "<p><strong>Erreurs:</strong> " . count($results['errors']) . "</p>\n";
    
    if (!empty($results['details'])) {
        echo "<h4>Détails :</h4>\n";
        echo "<ul>\n";
        foreach ($results['details'] as $detail) {
            echo "<li>{$detail}</li>\n";
        }
        echo "</ul>\n";
    }
    
    if (!empty($results['errors'])) {
        echo "<h4>Erreurs :</h4>\n";
        echo "<ul style='color: red;'>\n";
        foreach ($results['errors'] as $error) {
            echo "<li>{$error}</li>\n";
        }
        echo "</ul>\n";
    }
    
    // Afficher les statistiques après
    echo "<h3>Statistiques après traitement :</h3>\n";
    $statsAfter = $creator->getStatistics();
    if (isset($statsAfter['error'])) {
        echo "<p style='color: red;'>Erreur: {$statsAfter['error']}</p>\n";
    } else {
        echo "<ul>\n";
        echo "<li>Total étudiants: {$statsAfter['total_students']}</li>\n";
        echo "<li>Étudiants avec compte: {$statsAfter['students_with_account']}</li>\n";
        echo "<li>Étudiants sans compte: {$statsAfter['students_without_account']}</li>\n";
        echo "</ul>\n";
    }
}
?>