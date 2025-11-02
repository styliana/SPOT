<?php

require_once 'AppController.php';

class BookingsController extends AppController {

    public function mybookings() {
        // W przyszłości tutaj pobierzesz rezerwacje z bazy danych
        // $bookings = $this->bookingRepository->getBookingsForUser($userId);
        
        // === FAZA 1: "Fałszywe" dane rezerwacji ===
        $bookingsData = [
            [
                'id' => 1,
                'room_name' => 'Aula A1 (AULA1)',
                'date' => 'Oct 25, 2025',
                'time' => '10:00 - 12:00',
                'type' => 'Lecture Hall',
                'type_pill' => 'pill-blue',
                'status' => 'Confirmed',
                'status_pill' => 'pill-green'
            ],
            [
                'id' => 2,
                'room_name' => 'Pokój Cichej Nauki (STUDYROOM1)',
                'date' => 'Oct 26, 2025',
                'time' => '14:00 - 15:00',
                'type' => 'Study Room',
                'type_pill' => 'pill-gray',
                'status' => 'Pending',
                'status_pill' => 'pill-orange'
            ],
            [
                'id' => 3,
                'room_name' => 'Pokój ROOM4',
                'date' => 'Oct 28, 2025',
                'time' => '09:00 - 11:00',
                'type' => 'Lab',
                'type_pill' => 'pill-gray',
                'status' => 'Cancelled',
                'status_pill' => 'pill-red'
            ]
        ];
        // === KONIEC FAŁSZYWYCH DANYCH ===
        
        // Renderujemy widok, przekazując mu tablicę z rezerwacjami
        return $this->render('mybookings', ['bookings' => $bookingsData]);
    }
}
