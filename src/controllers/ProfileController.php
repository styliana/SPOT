<?php

require_once 'AppController.php';

class ProfileController extends AppController {

    public function myprofile() {
        // === ZABEZPIECZENIE ===
        $this->requireLogin();
        // ========================
        
        // W przyszłości pobierzesz dane zalogowanego użytkownika, np. z sesji lub bazy
        // $userId = $_SESSION['user_id'] ?? null;
        // $userData = $this->userRepository->getUserById($userId);
        
        // Na razie używamy danych przykładowych
        $userData = [
            'name' => $_SESSION['user_name'] ?? 'Brak imienia', // Pobieramy z sesji
            'email' => $_SESSION['user_email'] ?? 'brak@email.com', // Pobieramy z sesji
            'department' => 'Wydział Informatyki i Telekomunikacji',
            'role' => 'Student',
            'avatar_url' => null // Można dodać URL do obrazka awatara
        ];

        // Renderujemy widok, przekazując dane użytkownika
        return $this->render('myprofile', ['user' => $userData]);
    }
}