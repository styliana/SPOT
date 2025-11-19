<?php

require_once 'AppController.php';
require_once __DIR__ .'/../repository/UserRepository.php';

class UserController extends AppController {

    public function users() {
        // 1. Tworzymy instancję repozytorium
        $userRepository = new UserRepository();
        
        // 2. Pobieramy użytkowników metodą, którą napisałeś
        $users = $userRepository->getUsers(); //

        // 3. Wyświetlamy widok 'users' i przekazujemy mu dane
        // (Możesz też użyć var_dump($users); die(); aby tylko sprawdzić czy działa)
        return $this->render('users', ['users' => $users]);
    }
}