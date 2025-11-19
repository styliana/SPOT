<?php

// require_once 'Repository.php'; // To jest zbędne, bo Routing.php już to ładuje, ale jeśli zostawisz, też nie zaszkodzi.

class UserRepository extends Repository
{
    public function getUsers(): ?array
    {
        // Pobieramy połączenie z klasy nadrzędnej (Repository)
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM public.users
        ');
        
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }
}