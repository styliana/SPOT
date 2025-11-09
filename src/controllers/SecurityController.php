<?php

require_once 'AppController.php'; 

class SecurityController extends AppController {

    // === MOCK-DANE UŻYTKOWNIKÓW ===
    private static $mockUsers = [
        
        // 1. Użytkownik 'user@spot.com' (Hasło: 'password123')
        'user@spot.com' => [
            'username' => 'Test User',
            'email' => 'user@spot.com',
            // Hash dla 'password123', który wygenerowałeś
            'password_hash' => '$2y$10$3/5h0iT1sLn6Sz4vKnZut.O7mNAIqelXnTpinqqdFRftCMLYQVd96'
        ],

        // 2. NOWY Użytkownik 'student@spot.com' (Hasło: 'student123')
        'student@spot.com' => [
            'username' => 'Test Student',
            'email' => 'student@spot.com',
            // Twój hash dla 'student123'
            'password_hash' => '$2y$10$XMhp6GidjrpAJ9r.7gIVgOEXHi5fIk1uokOAZ7WZfUy9wyeOqxdcK'
        ],

        // 3. NOWY Użytkownik 'admin@spot.com' (Hasło: 'admin123')
        'admin@spot.com' => [
            'username' => 'Admin User',
            'email' => 'admin@spot.com',
            // Twój hash dla 'admin123'
            'password_hash' => '$2y$10$tRcFv2dZHIb3AtS0pYlE9OjsMhJR1PH1jfI4WvLpz2P4LgCNaZnr6'
        ]
    ];

    public function login() {
        if (isset($_SESSION['user_email'])) {
            return $this->redirect('/mybookings');
        }

        if (!$this->isPost()) {
            return $this->render("login");
        }

        // --- Obsługa logiki formularza POST ---
        error_log("SecurityController (FINAL): Próba logowania POST.");

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = self::$mockUsers[$email] ?? null;

        if (!$user) {
            // SCENARIUSZ: Użytkownik nie istnieje
            error_log("SecurityController (FINAL): Logowanie nieudane. Użytkownik nie znaleziony: $email");
            return $this->render('login', ['message' => 'Użytkownik o tym emailu nie istnieje.']);
        }

        // 3. Weryfikacja hasła
        if (password_verify($password, $user['password_hash'])) {
            
            // SCENARIUSZ: Poprawne logowanie
            error_log("SecurityController (FINAL): Logowanie UDANE. Użytkownik: $email");
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['username'];
            
            return $this->redirect('/mybookings');

        } else {
            
            // SCENARIUSZ: Niepoprawne hasło
            error_log("SecurityController (FINAL): Logowanie nieudane. Błędne hasło dla: $email");
            return $this->render('login', ['message' => 'Niepoprawne hasło. Spróbuj ponownie.']);
        }
    }

    public function register() {
        // === PRZYWRÓCONA, NORMALNA FUNKCJA REJESTRACJI ===
        if (!$this->isPost()) {
            return $this->render('register');
        }
        
        error_log("SecurityController: Próba rejestracji POST.");
        
        $email = $_POST['email'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($password !== $confirm_password) {
            error_log("SecurityController: Rejestracja nieudana. Hasła nie są identyczne.");
            return $this->render('register', ['message' => 'Hasła nie są identyczne!']);
        }

        if (empty($email) || empty($username) || empty($password)) {
             error_log("SecurityController: Rejestracja nieudana. Puste pola.");
             return $this->render('register', ['message' => 'Wszystkie pola są wymagane!']);
        }

        // Sprawdzamy, czy użytkownik już istnieje w naszej mock-tablicy
        if (isset(self::$mockUsers[$email])) {
            error_log("SecurityController: Rejestracja nieudana. Użytkownik już istnieje: $email");
            return $this->render('register', ['message' => 'Użytkownik o tym emailu już istnieje!']);
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Ta linia tylko dodaje użytkownika do tablicy na czas działania skryptu.
        // Po restarcie serwera ten nowy użytkownik zniknie.
        self::$mockUsers[$email] = [
            'username' => $username,
            'email' => $email,
            'password_hash' => $hashedPassword
        ];
        
        error_log("SecurityController: Rejestracja UDANA. Użytkownik: $email");
        return $this->render('login', ['message' => 'Rejestracja pomyślna! Możesz się teraz zalogować.']);
    }
    
    public function logout()
    {
        error_log("SecurityController: Wylogowano użytkownika: " . ($_SESSION['user_email'] ?? 'nieznany'));
        
        session_unset(); 
        session_destroy(); 

        return $this->redirect('/login');
    }
}