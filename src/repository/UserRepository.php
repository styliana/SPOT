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

        if ($user == false) return null;

        return $this->mapToUser($user);
    }

    public function addUser(User $user): void
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (email, password, name, surname, id_role)
            VALUES (?, ?, ?, ?, (SELECT id FROM roles WHERE name = ?))
        ');
        $stmt->execute([
            $user->getEmail(), $user->getPassword(), $user->getName(), $user->getSurname(), $user->getRole()
        ]);
    }

    // === METODY DLA ADMINA ===

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
            $result[] = $this->mapToUser($user);
        }
        return $result;
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
        
        if ($user == false) return null;
        
        return $this->mapToUser($user);
    }

    public function updateUser(User $user): void
    {
        $stmt = $this->database->connect()->prepare('
            UPDATE users SET name = ?, surname = ?, password = ? WHERE id = ?
        ');
        $stmt->execute([
            $user->getName(), $user->getSurname(), $user->getPassword(), $user->getId()
        ]);
    }

    public function updateUserByAdmin(int $id, string $name, string $surname, string $email): void {
        $stmt = $this->database->connect()->prepare('
            UPDATE users 
            SET name = :name, surname = :surname, email = :email
            WHERE id = :id
        ');
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateUserRole(int $userId, string $newRoleName): void {
        $stmt = $this->database->connect()->prepare('
            UPDATE users SET id_role = (SELECT id FROM roles WHERE name = :role) WHERE id = :id
        ');
        $stmt->bindParam(':role', $newRoleName, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteUser(int $id): void {
        $stmt = $this->database->connect()->prepare('DELETE FROM bookings WHERE user_id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt = $this->database->connect()->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function getUsers(): array { return $this->getAllUsers(); }

    // === Helper Mapping Method ===
    private function mapToUser(array $user): User {
        return new User(
            $user['email'], 
            $user['password'], 
            $user['name'], 
            $user['surname'],
            $user['role_name'] ?? 'student', 
            $user['id']
        );
    }
}