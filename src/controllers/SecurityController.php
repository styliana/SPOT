<?php

require_once 'AppController.php'; // Poprawnie! (są w tym samym folderze)

class SecurityController extends AppController {

    public function login() {
        return $this->render("login");
    }

    public function register() {
        if (!$this->isPost()) {
            return $this->render('register');
        }

        // ... logika rejestracji ...

        return $this->render('login', ['message' => 'Zarejestrowano pomyślnie!']);
    }
}