<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository
{
    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM public.users WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        // === TU BYŁ BŁĄD ===
        // Musimy przekazać wszystkie parametry do konstruktora User:
        // email, hasło, imię, nazwisko, ROLA, ID
        return new User(
            $user['email'],
            $user['password'],
            $user['firstname'],
            $user['lastname'],
            'student',   // Domyślna rola (bo na razie nie mamy jej w bazie)
            $user['id']  // <--- KLUCZOWE! Przekazujemy ID z bazy
        );
    }

    public function addUser(User $user): void
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (email, password, firstname, lastname)
            VALUES (?, ?, ?, ?)
        ');

        $stmt->execute([
            $user->getEmail(),
            $user->getPassword(),
            $user->getName(),
            $user->getSurname()
        ]);
    }
    
    // Opcjonalnie: metoda do pobierania wszystkich userów (dla /users)
    public function getUsers(): array
    {
        $result = [];
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM public.users
        ');
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as $user) {
            $result[] = new User(
                $user['email'],
                $user['password'],
                $user['firstname'],
                $user['lastname'],
                'student',
                $user['id']
            );
        }
        return $result;
    }
}