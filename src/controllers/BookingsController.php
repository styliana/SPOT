<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/BookingRepository.php';

class BookingsController extends AppController {

    private $bookingRepository;

    public function __construct()
    {
        parent::__construct();
        $this->bookingRepository = new BookingRepository();
    }

    public function mybookings() {
        // 1. Sprawdzamy, czy użytkownik jest zalogowany
        $this->requireLogin();
        
        // 2. Pobieramy ID użytkownika z sesji
        $userId = $_SESSION['user_id'];

        // 3. Pobieramy rezerwacje z bazy danych
        $bookingsObjects = $this->bookingRepository->getBookingsByUserId($userId);
        
        // 4. Mapujemy obiekty na tablicę dla widoku
        $bookingsData = [];
        
        foreach ($bookingsObjects as $booking) {
            // Ustalamy kolor statusu
            $statusPill = 'pill-gray';
            if ($booking->getStatus() === 'Confirmed') {
                $statusPill = 'pill-green';
            } elseif ($booking->getStatus() === 'Cancelled') {
                $statusPill = 'pill-red';
            } elseif ($booking->getStatus() === 'Pending') {
                $statusPill = 'pill-orange';
            }

            $bookingsData[] = [
                'id' => $booking->getId(),
                'room_id' => $booking->getRoomId(), // Potrzebne do linku edycji
                'room_name' => $booking->getRoomName(),
                'date' => $booking->getDate(),
                'time' => $booking->getTimeRange(),
                'type' => $booking->getRoomType(),
                'type_pill' => 'pill-blue', 
                'status' => $booking->getStatus(),
                'status_pill' => $statusPill
            ];
        }
        
        // 5. Renderujemy widok
        return $this->render('mybookings', ['bookings' => $bookingsData]);
    }

    public function delete() {
        $this->requireLogin();

        if (!$this->isPost()) {
            return $this->redirect('/mybookings');
        }

        $bookingId = $_POST['id'];
        $this->bookingRepository->deleteBooking($bookingId);

        return $this->redirect('/mybookings');
    }
}