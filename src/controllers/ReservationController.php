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

        // 1. Jeśli to wysłanie formularza (ZAPIS REZERWACJI)
        if ($this->isPost()) {
            $date = $_POST['date'];
            $startTime = $_POST['start_time'];
            $endTime = $_POST['end_time'];
            $roomId = $_POST['room_id'];
            $userId = $_SESSION['user_id'];

            // Walidacja (czy pokój nadal wolny?)
            $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime);
            if (in_array($roomId, $bookedRooms)) {
                return $this->render('reservation', ['message' => 'Ups! Ktoś właśnie zajął ten pokój. Wybierz inny.']);
            }

            // Zapis do bazy
            $this->bookingRepository->addBooking($userId, $roomId, $date, $startTime, $endTime);

            // Przekierowanie do My Bookings po sukcesie
            return $this->redirect('/mybookings');
        }

        // 2. Jeśli to zwykłe wejście na stronę (GET)
        // Przekazujemy parametry, jeśli wróciłem z "Choose this room"
        $variables = [
            'selected_room_id' => $_GET['room_id'] ?? null,
            'selected_date' => $_GET['date'] ?? '',
            'selected_start' => $_GET['start'] ?? '',
            'selected_end' => $_GET['end'] ?? ''
        ];

        return $this->render('reservation', $variables);
    }

    // === NOWA METODA API: Sprawdź dostępność (dla JS) ===
    public function checkAvailability() {
        // Odczytujemy dane JSON wysłane przez JavaScript
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        
        if ($contentType === "application/json") {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);

            $date = $decoded['date'];
            $startTime = $decoded['start_time'];
            $endTime = $decoded['end_time'];

            // Pobieramy zajęte pokoje
            $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime);

            // Wysyłamy odpowiedź JSON
            header('Content-Type: application/json');
            echo json_encode($bookedRooms);
            exit(); // Kończymy, żeby nie renderować widoku HTML
        }
    }
}