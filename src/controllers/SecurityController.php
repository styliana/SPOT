<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController {

    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $userRepository = new UserRepository();
        $this->userRepository = $userRepository;
    }

    public function login()
    {
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        // 1. Pobieramy użytkownika z bazy
        $user = $this->userRepository->getUser($email);

        // 2. Sprawdzamy czy użytkownik istnieje
        if (!$user) {
            return $this->render('login', ['message' => 'Użytkownik o tym emailu nie istnieje!']);
        }

        // 3. Weryfikujemy hasło (porównujemy wpisane z hashem z bazy)
        if ($user->getEmail() !== $email) {
            return $this->render('login', ['message' => 'Użytkownik o tym emailu nie istnieje!']);
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['message' => 'Błędne hasło!']);
        }

        // 4. Logujemy (zapisujemy w sesji)
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_name'] = $user->getName(); // Imię do wyświetlania "Hello, Jan!"
        
        // Przekierowanie
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/mybookings");
    }

    public function register()
    {
        if (!$this->isPost()) {
            return $this->render('register');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmedPassword = $_POST['confirm_password'];
        $username = $_POST['username']; // Traktujemy to jako imię (firstname) w tym przykładzie

        if ($password !== $confirmedPassword) {
            return $this->render('register', ['message' => 'Hasła nie są identyczne!']);
        }

        // TODO: Tutaj warto dodać sprawdzanie, czy email już istnieje (getUser)

        // HASZOWANIE HASŁA
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Tworzymy obiekt (email, hasło, imię, nazwisko - na razie puste nazwisko)
        $user = new User($email, $hashedPassword, $username, 'User');

        // Zapisujemy w bazie PostgreSQL
        $this->userRepository->addUser($user);

        return $this->render('login', ['message' => 'Rejestracja udana! Zaloguj się.']);
    }
    
    public function logout()
    {
        session_unset();
        session_destroy();
        return $this->render('login', ['message' => 'Wylogowano poprawnie.']);
    }
}