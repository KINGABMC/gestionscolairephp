<?php
require_once "../src/repository/UserRepository.php";

class AuthService {
    private UserRepository $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function login(string $email, string $password): User|null {
        $user = $this->userRepository->findByEmail($email);
        if ($user && $this->verifyPassword($password, $user->getPassword())) { 
            return $user;
        }
        return null;
    }

    private function verifyPassword(string $password, string $hashedPassword): bool {
        // Pour les mots de passe de test simples
        if ($password === $hashedPassword) {
            return true;
        }
        // Pour les mots de passe hashés
        return password_verify($password, $hashedPassword);
    }

    public function register(User $user): bool {
        // Hash du mot de passe avant insertion
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $user->setPassword($hashedPassword);
        return $this->userRepository->insert($user) > 0;
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['user']);
    }

    public function getCurrentUser(): User|null {
        return $_SESSION['user'] ?? null;
    }

    public function logout(): void {
        unset($_SESSION['user']);
        session_destroy();
    }

    public function hasRole(string $role): bool {
        $user = $this->getCurrentUser();
        return $user && $user->getRole() === $role;
    }

    public function hasAnyRole(array $roles): bool {
        $user = $this->getCurrentUser();
        return $user && in_array($user->getRole(), $roles);
    }
}