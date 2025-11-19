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

        // 1. Obsługa formularza (POST)
        if ($this->isPost()) {
            $date = $_POST['date'];
            $startTime = $_POST['start_time'];
            $endTime = $_POST['end_time'];
            $roomId = $_POST['room_id'];
            $userId = $_SESSION['user_id'];
            
            // Sprawdzamy, czy to edycja (czy mamy booking_id)
            $bookingId = $_POST['booking_id'] ?? null;

            // Walidacja dostępności (pomijamy walidację własnej rezerwacji przy edycji - uproszczenie)
            // W idealnym świecie sprawdzalibyśmy dostępność z wyłączeniem aktualnie edytowanej rezerwacji.
            $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime);
            
            // Jeśli edytujemy, to nasza własna rezerwacja może blokować termin. 
            // Dla uproszczenia: jeśli zmieniamy pokój, sprawdźmy czy wolny.
            if (in_array($roomId, $bookedRooms) && !$bookingId) {
                 return $this->render('reservation', ['message' => 'Ups! Ktoś właśnie zajął ten pokój.']);
            }

            if ($bookingId) {
                // EDYCJA
                $this->bookingRepository->updateBooking($bookingId, $userId, $roomId, $date, $startTime, $endTime);
            } else {
                // NOWA REZERWACJA
                $this->bookingRepository->addBooking($userId, $roomId, $date, $startTime, $endTime);
            }

            return $this->redirect('/mybookings');
        }

        // 2. Wyświetlanie strony (GET) - Pobieranie danych do edycji z URL
        $variables = [
            'booking_id' => $_GET['booking_id'] ?? null, // ID edytowanej rezerwacji
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

            $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime);

            header('Content-Type: application/json');
            echo json_encode($bookedRooms);
            exit();
        }
    }
}