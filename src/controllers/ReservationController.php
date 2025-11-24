<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/BookingRepository.php';

class ReservationController extends AppController {

    private $bookingRepository;

    public function __construct() {
        parent::__construct();
        $this->bookingRepository = new BookingRepository();
    }

    public function reservation() { 
        $this->requireLogin();

        // --- ZAPIS (POST) ---
        if ($this->isPost()) {
            $date = $_POST['date'];
            $startTime = $_POST['start_time'];
            $endTime = $_POST['end_time'];
            $roomId = $_POST['room_id'];
            
            $bookingId = $_POST['booking_id'] ?? null;
            if ($bookingId === '') $bookingId = null;

            $ownerId = $_POST['booking_owner_id'] ?? $_SESSION['user_id'];
            if ($ownerId === '') $ownerId = $_SESSION['user_id'];

            try {
                $bookingStart = new DateTime($date . ' ' . $startTime);
                $bookingEnd = new DateTime($date . ' ' . $endTime);
                $now = new DateTime();
                
                if ($bookingStart < $now) return $this->renderWithData('Nie można rezerwować w przeszłości!', $date, $startTime, $endTime, $roomId, $bookingId, $ownerId);
                if ($bookingEnd <= $bookingStart) return $this->renderWithData('Koniec musi być po starcie!', $date, $startTime, $endTime, $roomId, $bookingId, $ownerId);
            } catch (Exception $e) {}

            $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime, $bookingId);
            
            if (in_array($roomId, $bookedRooms)) {
                return $this->renderWithData('Ten pokój jest już zajęty!', $date, $startTime, $endTime, $roomId, $bookingId, $ownerId);
            }

            if ($bookingId) {
                $this->bookingRepository->updateBooking((int)$bookingId, (int)$ownerId, $roomId, $date, $startTime, $endTime);
            } else {
                $this->bookingRepository->addBooking((int)$ownerId, $roomId, $date, $startTime, $endTime);
            }

            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                return $this->redirect('/admin_bookings');
            } else {
                return $this->redirect('/mybookings');
            }
        }

        // --- WYŚWIETLANIE (GET) ---
        $bookingId = $_GET['booking_id'] ?? null;
        if ($bookingId === '') $bookingId = null;

        $bookingOwnerId = null;
        if ($bookingId) {
            $existingBooking = $this->bookingRepository->getBookingById($bookingId);
            if ($existingBooking) {
                $bookingOwnerId = $existingBooking->getUserId();
            }
        }

        $variables = [
            'booking_id' => $bookingId,
            'booking_owner_id' => $bookingOwnerId,
            'selected_room_id' => $_GET['room_id'] ?? null,
            'selected_date' => $_GET['date'] ?? '',
            'selected_start' => $_GET['start'] ?? '',
            'selected_end' => $_GET['end'] ?? ''
        ];

        return $this->render('reservation', $variables);
    }

    public function checkAvailability() {
        // Zawsze ustawiamy nagłówek JSON
        header('Content-Type: application/json');

        // Pobieramy surowe dane wejściowe (ignorujemy nagłówki przeglądarki, żeby było bezpieczniej)
        $content = file_get_contents("php://input");
        $decoded = json_decode($content, true);
        
        if (is_array($decoded)) {
            $date = $decoded['date'] ?? null;
            $startTime = $decoded['start_time'] ?? null;
            $endTime = $decoded['end_time'] ?? null;
            $bookingId = $decoded['booking_id'] ?? null; // Może być null

            if ($date && $startTime && $endTime) {
                $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime, $bookingId);
                echo json_encode($bookedRooms);
                exit();
            }
        }
        
        // Jeśli dane są niekompletne, zwracamy pustą tablicę
        echo json_encode([]);
        exit();
    }

    private function renderWithData($message, $date, $start, $end, $roomId, $bookingId, $ownerId) {
        return $this->render('reservation', [
            'message' => $message,
            'selected_date' => $date,
            'selected_start' => $start,
            'selected_end' => $end,
            'selected_room_id' => $roomId,
            'booking_id' => $bookingId,
            'booking_owner_id' => $ownerId
        ]);
    }
}