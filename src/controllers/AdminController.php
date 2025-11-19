<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/RoomRepository.php';
require_once __DIR__ . '/../repository/BookingRepository.php';

class AdminController extends AppController {

    private $userRepository;
    private $roomRepository;
    private $bookingRepository;

    public function __construct() {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->roomRepository = new RoomRepository();
        $this->bookingRepository = new BookingRepository();
    }

    private function checkAdmin() {
        $this->requireLogin();
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/mybookings');
            exit();
        }
    }

    // ... (admin_users, admin_rooms, admin_bookings, delete... - BEZ ZMIAN) ...
    public function admin_users() { $this->checkAdmin(); $users = $this->userRepository->getAllUsers(); return $this->render('admin_users', ['users' => $users]); }
    public function admin_rooms() { $this->checkAdmin(); if($this->isPost()){ $r = new Room($_POST['id'],$_POST['name'],(int)$_POST['workspaces'],$_POST['type'],$_POST['description']); $this->roomRepository->addRoom($r); return $this->redirect('/admin_rooms'); } $rooms = $this->roomRepository->getRooms(); return $this->render('admin_rooms', ['rooms' => $rooms]); }
    public function admin_bookings() { $this->checkAdmin(); $bookings = $this->bookingRepository->getAllBookings(); return $this->render('admin_bookings', ['bookings' => $bookings]); }
    public function admin_delete_user() { $this->checkAdmin(); if($this->isPost()){ $this->userRepository->deleteUser($_POST['id']); } $this->redirect('/admin_users'); }
    public function admin_delete_room() { $this->checkAdmin(); if($this->isPost()){ $this->roomRepository->deleteRoom($_POST['id']); } $this->redirect('/admin_rooms'); }
    public function admin_delete_booking() { $this->checkAdmin(); if($this->isPost()){ $this->bookingRepository->deleteBooking($_POST['id']); } $this->redirect('/admin_bookings'); }
    public function admin_change_role() { $this->checkAdmin(); if($this->isPost()){ $this->userRepository->updateUserRole($_POST['id'], $_POST['role']); } $this->redirect('/admin_users'); }

    // === EDYCJA UŻYTKOWNIKA PRZEZ ADMINA ===

    public function admin_edit_user() {
        $this->checkAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) return $this->redirect('/admin_users');
        
        $user = $this->userRepository->getUserById($id);
        if (!$user) return $this->redirect('/admin_users');

        return $this->render('admin_edit_user', ['user' => $user]);
    }

    public function admin_update_user() {
        $this->checkAdmin();
        if ($this->isPost()) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $email = $_POST['email'];
            
            // Wywołujemy metodę z repozytorium
            $this->userRepository->updateUserByAdmin($id, $name, $surname, $email);
            
            return $this->redirect('/admin_users');
        }
        return $this->redirect('/admin_users');
    }
}