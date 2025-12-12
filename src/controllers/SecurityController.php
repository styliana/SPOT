<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController {

    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function login()
    {
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userRepository->getUser($email);

        if (!$user) {
            return $this->render('login', ['message' => 'Użytkownik o tym emailu nie istnieje!']);
        }

        if ($user->getEmail() !== $email) {
            return $this->render('login', ['message' => 'Użytkownik o tym emailu nie istnieje!']);
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['message' => 'Błędne hasło!']);
        }

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_name'] = $user->getName();
        $_SESSION['user_surname'] = $user->getSurname();
        $_SESSION['user_role'] = $user->getRole(); 

        if ($user->getRole() === 'admin') {
            return $this->redirect('/admin_users');
        }

        return $this->redirect('/mybookings');
    }

    public function register()
    {
        if (!$this->isPost()) {
            return $this->render('register');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmedPassword = $_POST['confirm_password'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];

        if ($password !== $confirmedPassword) {
            return $this->render('register', ['message' => 'Hasła nie są identyczne!']);
        }

        if (empty($email) || empty($name) || empty($surname) || empty($password)) {
             return $this->render('register', ['message' => 'Wszystkie pola są wymagane!']);
        }

        $existingUser = $this->userRepository->getUser($email);
        if ($existingUser) {
             return $this->render('register', ['message' => 'Taki email jest już zajęty!']);
        }

        $user = new User($email, password_hash($password, PASSWORD_BCRYPT), $name, $surname, 'student');

        $this->userRepository->addUser($user);

        return $this->render('login', ['message' => 'Rejestracja udana! Zaloguj się.']);
    }
    
    public function logout()
    {
        session_unset();
        session_destroy();
        return $this->redirect('/login');
    }
}