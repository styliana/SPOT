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
            $roomId = isset($_POST['room_id']) ? trim($_POST['room_id']) : '';
            
            $bookingId = $_POST['booking_id'] ?? null;
            if ($bookingId === '') $bookingId = null;

            $ownerId = $_POST['booking_owner_id'] ?? $_SESSION['user_id'];
            if ($ownerId === '') $ownerId = $_SESSION['user_id'];

            if (empty($roomId)) {
                return $this->renderWithData('Proszę wybrać pokój z mapy!', $date, $startTime, $endTime, $roomId, $bookingId, $ownerId);
            }

            // --- POCZĄTEK TRANSAKCJI ---
            try {
                $this->bookingRepository->beginTransaction();

                // 1. Walidacja logiczna (daty)
                $bookingStart = new DateTime($date . ' ' . $startTime);
                $bookingEnd = new DateTime($date . ' ' . $endTime);
                $now = new DateTime();
                
                if ($bookingStart < $now) throw new Exception('Nie można rezerwować w przeszłości!');
                if ($bookingEnd <= $bookingStart) throw new Exception('Godzina zakończenia musi być późniejsza niż rozpoczęcia!');

                // 2. Sprawdzenie dostępności w bazie (wewnątrz transakcji)
                // W idealnym świecie użylibyśmy tu "SELECT FOR UPDATE", ale zwykły select w transakcji też jest krokiem naprzód
                $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime, (int)$bookingId);
                
                if (in_array($roomId, $bookedRooms)) {
                    throw new Exception('Niestety, ten pokój został właśnie zajęty.');
                }

                // 3. Zapis / Aktualizacja
                if ($bookingId) {
                    $this->bookingRepository->updateBooking((int)$bookingId, (int)$ownerId, $roomId, $date, $startTime, $endTime);
                } else {
                    $this->bookingRepository->addBooking((int)$ownerId, $roomId, $date, $startTime, $endTime);
                }

                // --- ZATWIERDZENIE TRANSAKCJI ---
                $this->bookingRepository->commit();

                // Przekierowanie po sukcesie
                if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                    return $this->redirect('/admin_bookings');
                } else {
                    return $this->redirect('/mybookings');
                }

            } catch (Exception $e) {
                // --- WYCOFANIE TRANSAKCJI W RAZIE BŁĘDU ---
                $this->bookingRepository->rollBack();
                
                // Wyświetlenie błędu użytkownikowi
                return $this->renderWithData($e->getMessage(), $date, $startTime, $endTime, $roomId, $bookingId, $ownerId);
            }
        }

        // --- WYŚWIETLANIE (GET) ---
        // ... (reszta kodu bez zmian, skopiuj z poprzedniej wersji) ...
        $bookingId = $_GET['booking_id'] ?? null;
        if ($bookingId === '') $bookingId = null;

        $bookingOwnerId = null;
        $existingBooking = null;

        if ($bookingId) {
            $existingBooking = $this->bookingRepository->getBookingById((int)$bookingId);
            if ($existingBooking) {
                $bookingOwnerId = $existingBooking->getUserId();
            }
        }

        $selectedRoomId = $_GET['room_id'] ?? null;
        $selectedDate   = $_GET['date'] ?? null;
        $selectedStart  = $_GET['start'] ?? null;
        $selectedEnd    = $_GET['end'] ?? null;

        if ($existingBooking) {
            if (!$selectedRoomId) $selectedRoomId = $existingBooking->getRoomId();
            if (!$selectedDate)   $selectedDate = $existingBooking->getDate();
            
            if (!$selectedStart || !$selectedEnd) {
                $times = explode(' - ', $existingBooking->getTimeRange());
                if (!$selectedStart) $selectedStart = $times[0] ?? '';
                if (!$selectedEnd)   $selectedEnd   = $times[1] ?? '';
            }
        }

        $variables = [
            'booking_id' => $bookingId,
            'booking_owner_id' => $bookingOwnerId,
            'selected_room_id' => $selectedRoomId,
            'selected_date' => $selectedDate,
            'selected_start' => $selectedStart,
            'selected_end' => $selectedEnd
        ];

        return $this->render('reservation', $variables);
    }
    
    // ... reszta metod (checkAvailability, renderWithData) bez zmian ...
    public function checkAvailability() {
        header('Content-Type: application/json');
        $content = file_get_contents("php://input");
        $decoded = json_decode($content, true);
        if (is_array($decoded)) {
            $date = $decoded['date'] ?? null;
            $startTime = $decoded['start_time'] ?? null;
            $endTime = $decoded['end_time'] ?? null;
            $bookingId = $decoded['booking_id'] ?? null;
            if ($date && $startTime && $endTime) {
                $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime, $bookingId ? (int)$bookingId : null);
                echo json_encode($bookedRooms);
                exit();
            }
        }
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