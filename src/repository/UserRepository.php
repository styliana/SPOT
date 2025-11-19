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
    
    public function getUsers(): array
    {
        $result = [];
        $stmt = $this->database->connect()->prepare('
            SELECT u.*, r.name as role_name 
            FROM users u 
            LEFT JOIN roles r ON u.id_role = r.id
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

    // === NOWA METODA: Pobieranie po ID ===
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

    // === NOWA METODA: Aktualizacja danych ===
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