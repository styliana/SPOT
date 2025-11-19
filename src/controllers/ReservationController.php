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

        if ($this->isPost()) {
            $date = $_POST['date'];
            $startTime = $_POST['start_time'];
            $endTime = $_POST['end_time'];
            $roomId = $_POST['room_id'];
            $userId = $_SESSION['user_id'];
            $bookingId = $_POST['booking_id'] ?? null; // Pobieramy ID (może być pusty string)
            
            // Konwersja pustego stringa na null dla pewności
            if ($bookingId === '') $bookingId = null;

            try {
                $bookingStart = new DateTime($date . ' ' . $startTime);
                $now = new DateTime();
                if ($bookingStart < $now) {
                    return $this->render('reservation', ['message' => 'Nie można dokonać rezerwacji w przeszłości!']);
                }
            } catch (Exception $e) {}

            // === PRZEKAZUJEMY ID DO WALIDACJI ===
            // Dzięki temu nie zablokujemy sami siebie
            $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime, $bookingId);
            
            if (in_array($roomId, $bookedRooms)) {
                 return $this->render('reservation', ['message' => 'Ups! Ktoś właśnie zajął ten pokój.']);
            }

            if ($bookingId) {
                $this->bookingRepository->updateBooking((int)$bookingId, $userId, $roomId, $date, $startTime, $endTime);
            } else {
                $this->bookingRepository->addBooking($userId, $roomId, $date, $startTime, $endTime);
            }

            return $this->redirect('/mybookings');
        }

        $variables = [
            'booking_id' => $_GET['booking_id'] ?? null,
            'selected_room_id' => $_GET['room_id'] ?? null,
            'selected_date' => $_GET['date'] ?? '',
            'selected_start' => $_GET['start'] ?? '',
            'selected_end' => $_GET['end'] ?? ''
        ];

        return $this->render('reservation', $variables);
    }

    public function checkAvailability() {
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if ($contentType === "application/json") {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);

            $date = $decoded['date'];
            $startTime = $decoded['start_time'];
            $endTime = $decoded['end_time'];
            $bookingId = $decoded['booking_id'] ?? null; // Pobieramy ID z JSONa

            // === PRZEKAZUJEMY ID DO API ===
            $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime, $bookingId);

            header('Content-Type: application/json');
            echo json_encode($bookedRooms);
            exit();
        }
    }
}