<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository
{
    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare('
            SELECT u.*, r.name as role_name 
            FROM users u 
            LEFT JOIN roles r ON u.id_role = r.id
            WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User(
            $user['email'],
            $user['password'],
            $user['name'],
            $user['surname'],
            $user['role_name'] ?? 'student',
            $user['id']
        );
    }

    public function addUser(User $user): void
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (email, password, name, surname, id_role)
            VALUES (?, ?, ?, ?, (SELECT id FROM roles WHERE name = ?))
        ');

        $stmt->execute([
            $user->getEmail(),
            $user->getPassword(),
            $user->getName(),
            $user->getSurname(),
            $user->getRole()
        ]);
    }

    // === NOWOŚĆ: Pobieranie wszystkich userów (dla Admina) ===
    public function getAllUsers(): array
    {
        $result = [];
        $stmt = $this->database->connect()->prepare('
            SELECT u.*, r.name as role_name 
            FROM users u 
            LEFT JOIN roles r ON u.id_role = r.id
            ORDER BY u.id ASC
        ');
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as $user) {
            $result[] = new User(
                $user['email'],
                $user['password'],
                $user['name'],
                $user['surname'],
                $user['role_name'] ?? 'student',
                $user['id']
            );
        }
        return $result;
    }

    // === NOWOŚĆ: Usuwanie usera (dla Admina) ===
    public function deleteUser(int $id): void {
        // Najpierw usuwamy rezerwacje użytkownika
        $stmt = $this->database->connect()->prepare('DELETE FROM bookings WHERE user_id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Potem usuwamy użytkownika
        $stmt = $this->database->connect()->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    // === NOWOŚĆ: Zmiana roli (dla Admina) ===
    public function updateUserRole(int $userId, string $newRoleName): void {
        $stmt = $this->database->connect()->prepare('
            UPDATE users 
            SET id_role = (SELECT id FROM roles WHERE name = :role)
            WHERE id = :id
        ');
        $stmt->bindParam(':role', $newRoleName, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function getUsers(): array {
        return $this->getAllUsers(); // Alias dla kompatybilności
    }
    
    public function getUserById(int $id): ?User
    {
        $stmt = $this->database->connect()->prepare('
            SELECT u.*, r.name as role_name 
            FROM users u 
            LEFT JOIN roles r ON u.id_role = r.id
            WHERE u.id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User(
            $user['email'],
            $user['password'],
            $user['name'],
            $user['surname'],
            $user['role_name'] ?? 'student',
            $user['id']
        );
    }

    public function updateUser(User $user): void
    {
        $stmt = $this->database->connect()->prepare('
            UPDATE users 
            SET name = ?, surname = ?, password = ?
            WHERE id = ?
        ');

        $stmt->execute([
            $user->getName(),
            $user->getSurname(),
            $user->getPassword(),
            $user->getId()
        ]);
    }
}