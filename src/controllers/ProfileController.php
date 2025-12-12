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

    public function myprofile() {
        $this->requireLogin();
        
        $userId = $_SESSION['user_id'];
        $user = $this->userRepository->getUserById($userId);

        if (!$user) {
            return $this->render('login', ['message' => 'Sesja wygasła. Zaloguj się ponownie.']);
        }

        return $this->render('myprofile', ['user' => $user]);
    }

    public function edit() {
        $this->requireLogin();
        
        $userId = $_SESSION['user_id'];
        $user = $this->userRepository->getUserById($userId);

        if (!$user) {
            return $this->redirect('/login');
        }

        return $this->render('edit_profile', ['user' => $user]);
    }

    public function update() {
        $this->requireLogin();

        if (!$this->isPost()) {
            return $this->redirect('/myprofile');
        }

        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];
        $confirmNewPassword = $_POST['confirm_new_password'];

        $userId = $_SESSION['user_id'];
        $currentUser = $this->userRepository->getUserById($userId);

        if (!$currentUser) {
            return $this->redirect('/login');
        }

        if (empty($oldPassword)) {
            return $this->render('edit_profile', ['user' => $currentUser, 'message' => 'Musisz podać stare hasło, aby zapisać zmiany!']);
        }

        if (!password_verify($oldPassword, $currentUser->getPassword())) {
            return $this->render('edit_profile', ['user' => $currentUser, 'message' => 'Stare hasło jest nieprawidłowe!']);
        }

        $finalPasswordHash = $currentUser->getPassword(); 

        if (!empty($newPassword)) {
            if ($newPassword !== $confirmNewPassword) {
                return $this->render('edit_profile', ['user' => $currentUser, 'message' => 'Nowe hasła nie są identyczne!']);
            }
            if (password_verify($newPassword, $finalPasswordHash)) {
                 return $this->render('edit_profile', ['user' => $currentUser, 'message' => 'Nowe hasło musi być inne niż poprzednie!']);
            }

            $finalPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        $updatedUser = new User(
            $currentUser->getEmail(),
            $finalPasswordHash,
            $name,
            $surname,
            $currentUser->getRole(),
            $userId
        );

        $this->userRepository->updateUser($updatedUser);

        $_SESSION['user_name'] = $name;
        $_SESSION['user_surname'] = $surname;

        return $this->render('myprofile', ['user' => $updatedUser, 'message' => 'Dane zostały zaktualizowane!']);
    }
}