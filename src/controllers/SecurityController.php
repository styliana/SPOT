<?php

require_once 'AppController.php';


class SecurityController extends AppController {


    public function login() {

        return $this->render("login");
    }

    public function register() {
        if (!$this->isPost()) {
            return $this->render('register');
        }

        return $this->render('login', ['message' => 'Zarejestrowano użytkownika']);
    }
}