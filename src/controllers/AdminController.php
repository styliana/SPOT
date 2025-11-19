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
        
        // Sprawdzamy czy user jest adminem
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            // Jeśli nie, wyrzucamy go na stronę rezerwacji lub pokazujemy 403
            $this->redirect('/mybookings');
            exit();
        }
    }

    public function admin_users() {
        $this->checkAdmin();
        $users = $this->userRepository->getAllUsers();
        return $this->render('admin_users', ['users' => $users]);
    }

    public function admin_rooms() {
        $this->checkAdmin();

        if ($this->isPost()) {
            // Dodawanie pokoju
            $id = $_POST['id'];
            $name = $_POST['name'];
            $workspaces = (int)$_POST['workspaces'];
            $type = $_POST['type'];
            $description = $_POST['description'];
            
            $room = new Room($id, $name, $workspaces, $type, $description);
            $this->roomRepository->addRoom($room);
            
            return $this->redirect('/admin_rooms');
        }

        $rooms = $this->roomRepository->getRooms();
        return $this->render('admin_rooms', ['rooms' => $rooms]);
    }

    public function admin_bookings() {
        $this->checkAdmin();
        $bookings = $this->bookingRepository->getAllBookings();
        return $this->render('admin_bookings', ['bookings' => $bookings]);
    }

    // === AKCJE USUWANIA ===
    public function admin_delete_user() {
        $this->checkAdmin();
        if ($this->isPost()) {
            $this->userRepository->deleteUser($_POST['id']);
        }
        $this->redirect('/admin_users');
    }

    public function admin_delete_room() {
        $this->checkAdmin();
        if ($this->isPost()) {
            $this->roomRepository->deleteRoom($_POST['id']);
        }
        $this->redirect('/admin_rooms');
    }

    public function admin_delete_booking() {
        $this->checkAdmin();
        if ($this->isPost()) {
            $this->bookingRepository->deleteBooking($_POST['id']);
        }
        $this->redirect('/admin_bookings');
    }
    
    public function admin_change_role() {
        $this->checkAdmin();
        if ($this->isPost()) {
            $this->userRepository->updateUserRole($_POST['id'], $_POST['role']);
        }
        $this->redirect('/admin_users');
    }
}