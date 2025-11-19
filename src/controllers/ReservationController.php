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
            
            // Pobieramy ID rezerwacji
            $bookingId = $_POST['booking_id'] ?? null;
            if ($bookingId === '') $bookingId = null;

            // === KLUCZOWE: KTO JEST WŁAŚCICIELEM? ===
            // Jeśli edytujemy, bierzemy ID z ukrytego pola (to właściciel rezerwacji)
            // Jeśli to nowa rezerwacja, bierzemy ID z sesji (zalogowany user)
            $ownerId = $_POST['booking_owner_id'] ?? $_SESSION['user_id'];
            if ($ownerId === '') $ownerId = $_SESSION['user_id'];
            // ========================================

            try {
                $bookingStart = new DateTime($date . ' ' . $startTime);
                $bookingEnd = new DateTime($date . ' ' . $endTime);
                $now = new DateTime();
                if ($bookingStart < $now) return $this->render('reservation', ['message' => 'Nie można rezerwować w przeszłości!']);
                if ($bookingEnd <= $bookingStart) return $this->render('reservation', ['message' => 'Koniec musi być po starcie!']);
            } catch (Exception $e) {}

            $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime, $bookingId);
            if (in_array($roomId, $bookedRooms)) return $this->render('reservation', ['message' => 'Pokój zajęty!']);

            if ($bookingId) {
                // Aktualizujemy dla WŁAŚCICIELA (ownerId), a nie dla admina
                $this->bookingRepository->updateBooking((int)$bookingId, (int)$ownerId, $roomId, $date, $startTime, $endTime);
            } else {
                $this->bookingRepository->addBooking((int)$ownerId, $roomId, $date, $startTime, $endTime);
            }

            // Przekierowanie zależne od roli ADMINA (zalogowanego)
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                return $this->redirect('/admin_bookings');
            } else {
                return $this->redirect('/mybookings');
            }
        }

        // --- WYŚWIETLANIE (GET) ---
        $bookingId = $_GET['booking_id'] ?? null;
        $bookingOwnerId = null;

        // Jeśli edytujemy, pobierzmy oryginalnego właściciela z bazy!
        if ($bookingId) {
            $existingBooking = $this->bookingRepository->getBookingById($bookingId);
            if ($existingBooking) {
                $bookingOwnerId = $existingBooking->getUserId();
            }
        }

        $variables = [
            'booking_id' => $bookingId,
            'booking_owner_id' => $bookingOwnerId, // Przekazujemy do widoku
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
            $bookingId = $decoded['booking_id'] ?? null;
            $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime, $bookingId);
            header('Content-Type: application/json');
            echo json_encode($bookedRooms);
            exit();
        }
    }
}