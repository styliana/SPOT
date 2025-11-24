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
        // Ta część obsługuje wysłanie formularza (zapisanie zmian)
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
                
                // Walidacja dat
                if ($bookingStart < $now) {
                    return $this->renderWithData('Nie można rezerwować w przeszłości!', $date, $startTime, $endTime, $roomId, $bookingId, $ownerId);
                }
                if ($bookingEnd <= $bookingStart) {
                    return $this->renderWithData('Koniec musi być po starcie!', $date, $startTime, $endTime, $roomId, $bookingId, $ownerId);
                }
            } catch (Exception $e) {}

            // Sprawdzenie dostępności (z wykluczeniem edytowanej rezerwacji, jeśli taka jest)
            $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime, (int)$bookingId);
            
            if (in_array($roomId, $bookedRooms)) {
                return $this->renderWithData('Ten pokój jest już zajęty w tym terminie!', $date, $startTime, $endTime, $roomId, $bookingId, $ownerId);
            }

            // Aktualizacja lub Dodawanie
            if ($bookingId) {
                $this->bookingRepository->updateBooking((int)$bookingId, (int)$ownerId, $roomId, $date, $startTime, $endTime);
            } else {
                $this->bookingRepository->addBooking((int)$ownerId, $roomId, $date, $startTime, $endTime);
            }

            // Przekierowanie zależne od roli
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                return $this->redirect('/admin_bookings');
            } else {
                return $this->redirect('/mybookings');
            }
        }

        // --- WYŚWIETLANIE (GET) ---
        // Ta część odpowiada za wyświetlenie formularza z danymi
        
        $bookingId = $_GET['booking_id'] ?? null;
        if ($bookingId === '') $bookingId = null;

        $bookingOwnerId = null;
        $existingBooking = null;

        // 1. Jeśli mamy ID rezerwacji, pobieramy ją z bazy
        if ($bookingId) {
            $existingBooking = $this->bookingRepository->getBookingById((int)$bookingId);
            if ($existingBooking) {
                $bookingOwnerId = $existingBooking->getUserId();
            }
        }

        // 2. Ustalamy wartości początkowe zmiennych
        // Priorytet: 
        // 1. Dane z URL (jeśli użytkownik właśnie kliknął na mapę lub zmienił datę)
        // 2. Dane z bazy (jeśli użytkownik kliknął "Edytuj" i dopiero wszedł na stronę)
        
        $selectedRoomId = $_GET['room_id'] ?? null;
        $selectedDate   = $_GET['date'] ?? null;
        $selectedStart  = $_GET['start'] ?? null;
        $selectedEnd    = $_GET['end'] ?? null;

        if ($existingBooking) {
            // Jeśli w URL brakuje ID pokoju, bierzemy z istniejącej rezerwacji
            if (!$selectedRoomId) {
                $selectedRoomId = $existingBooking->getRoomId();
            }
            // Jeśli w URL brakuje daty, bierzemy z rezerwacji
            if (!$selectedDate) {
                $selectedDate = $existingBooking->getDate();
            }
            // Jeśli w URL brakuje czasu, wyciągamy go z rezerwacji
            if (!$selectedStart || !$selectedEnd) {
                // Booking zwraca np. "10:00 - 12:00", musimy to rozdzielić
                $times = explode(' - ', $existingBooking->getTimeRange());
                if (!$selectedStart) $selectedStart = $times[0] ?? '';
                if (!$selectedEnd)   $selectedEnd   = $times[1] ?? '';
            }
        }

        // Przekazujemy zmienne do widoku
        $variables = [
            'booking_id' => $bookingId,
            'booking_owner_id' => $bookingOwnerId,
            'selected_room_id' => $selectedRoomId, // To trafi do inputa value="..."
            'selected_date' => $selectedDate,
            'selected_start' => $selectedStart,
            'selected_end' => $selectedEnd
        ];

        return $this->render('reservation', $variables);
    }

    // API do sprawdzania dostępności (używane przez JavaScript)
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
                // Pobierz zajęte pokoje, ale ignoruj aktualnie edytowaną rezerwację ($bookingId)
                $bookedRooms = $this->bookingRepository->getBookedRoomIds(
                    $date, 
                    $startTime, 
                    $endTime, 
                    $bookingId ? (int)$bookingId : null
                );
                echo json_encode($bookedRooms);
                exit();
            }
        }
        
        echo json_encode([]);
        exit();
    }

    // Pomocnicza metoda do renderowania formularza z komunikatem błędu i zachowaniem wpisanych danych
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