<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../models/User.php';

class ProfileController extends AppController {

    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    // Wyświetlanie profilu (READ)
    public function myprofile() {
        $this->requireLogin();
        
        // Pobieramy świeże dane z bazy
        $userId = $_SESSION['user_id'];
        $user = $this->userRepository->getUserById($userId);

        if (!$user) {
            return $this->render('login', ['message' => 'Sesja wygasła. Zaloguj się ponownie.']);
        }

        return $this->render('myprofile', ['user' => $user]);
    }

    // Wyświetlanie formularza edycji (GET)
    public function edit() {
        $this->requireLogin();
        
        $userId = $_SESSION['user_id'];
        $user = $this->userRepository->getUserById($userId);

        if (!$user) {
            return $this->redirect('/login');
        }

        return $this->render('edit_profile', ['user' => $user]);
    }

    // Przetwarzanie formularza edycji (POST)
    public function update() {
        $this->requireLogin();

        if (!$this->isPost()) {
            return $this->redirect('/myprofile');
        }

        // 1. Pobierz dane z formularza
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];
        $confirmNewPassword = $_POST['confirm_new_password'];

        // 2. Pobierz aktualnego użytkownika z bazy
        $userId = $_SESSION['user_id'];
        $currentUser = $this->userRepository->getUserById($userId);

        if (!$currentUser) {
            return $this->redirect('/login');
        }

        // 3. Walidacja: Czy podano stare hasło?
        if (empty($oldPassword)) {
            return $this->render('edit_profile', ['user' => $currentUser, 'message' => 'Musisz podać stare hasło, aby zapisać zmiany!']);
        }

        // 4. Walidacja: Czy stare hasło jest poprawne?
        if (!password_verify($oldPassword, $currentUser->getPassword())) {
            return $this->render('edit_profile', ['user' => $currentUser, 'message' => 'Stare hasło jest nieprawidłowe!']);
        }

        // 5. Logika zmiany hasła
        $finalPasswordHash = $currentUser->getPassword(); // Domyślnie zostaje stare

        if (!empty($newPassword)) {
            if ($newPassword !== $confirmNewPassword) {
                return $this->render('edit_profile', ['user' => $currentUser, 'message' => 'Nowe hasła nie są identyczne!']);
            }
            if (password_verify($newPassword, $finalPasswordHash)) {
                 return $this->render('edit_profile', ['user' => $currentUser, 'message' => 'Nowe hasło musi być inne niż poprzednie!']);
            }
            // Haszujemy nowe hasło
            $finalPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        // 6. Aktualizacja w bazie
        $updatedUser = new User(
            $currentUser->getEmail(),
            $finalPasswordHash,
            $name,
            $surname,
            $currentUser->getRole(),
            $userId
        );

        $this->userRepository->updateUser($updatedUser);

        // 7. Aktualizacja sesji (żeby nagłówek zaktualizował się od razu)
        $_SESSION['user_name'] = $name;
        $_SESSION['user_surname'] = $surname;

        // Sukces - wracamy do profilu
        return $this->render('myprofile', ['user' => $updatedUser, 'message' => 'Dane zostały zaktualizowane!']);
    }
}