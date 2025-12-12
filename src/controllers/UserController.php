<?php

require_once 'AppController.php';
require_once __DIR__ .'/../repository/UserRepository.php';

class UserController extends AppController {

    public function users() {
        $userRepository = new UserRepository();
        $users = $userRepository->getUsers(); 

        return $this->render('users', ['users' => $users]);
    }
}