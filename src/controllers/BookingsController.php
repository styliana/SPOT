<?php

require_once 'AppController.php';

class BookingsController extends AppController {

    public function mybookings() {
        // W przyszłości tutaj pobierzesz rezerwacje z bazy danych
        // $bookings = $this->bookingRepository->getBookingsForUser($userId);
        
        // Na razie po prostu renderujemy widok
        return $this->render('mybookings');
    }
}