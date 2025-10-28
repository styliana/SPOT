<?php

require_once 'AppController.php';

class ProfileController extends AppController {

    public function myprofile() {
        // W przyszłości pobierzesz dane zalogowanego użytkownika, np. z sesji lub bazy
        // $userId = $_SESSION['user_id'] ?? null;
        // if (!$userId) { /* przekieruj do logowania */ }
        // $userData = $this->userRepository->getUserById($userId);
        
        // Na razie używamy danych przykładowych
        $userData = [
            'name' => 'Jan Kowalski',
            'email' => 'jan.kowalski@student.example.com',
            'department' => 'Wydział Informatyki i Telekomunikacji',
            'role' => 'Student',
            'avatar_url' => null // Można dodać URL do obrazka awatara
        ];

        // Renderujemy widok, przekazując dane użytkownika
        return $this->render('myprofile', ['user' => $userData]);
    }
}