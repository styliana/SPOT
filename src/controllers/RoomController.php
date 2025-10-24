<?php

require_once 'AppController.php';

class RoomController extends AppController {

    // Metoda będzie wywołana dla URL typu /room/{id}
    public function room(string $roomId) {
        
        // W przyszłości: Pobierz dane o pokoju z bazy
        // $roomDetails = $this->roomRepository->getRoomById($roomId);
        
        // === PRZYKŁADOWE DANE (zastąp je danymi z bazy) ===
        $roomDetails = null;
        if ($roomId === 'CU001') {
             $roomDetails = [
                'id' => 'CU001',
                'name' => 'Room CU001', // Możesz dać ładniejszą nazwę, np. 'Sala Rektorska'
                'workspaces' => 25,
                'type' => 'Lecture room',
                'description' => 'Interactive board, broken door' 
             ];
        } elseif ($roomId === 'AULA1') {
             $roomDetails = [
                'id' => 'AULA1',
                'name' => 'Aula A1',
                'workspaces' => 150,
                'type' => 'Auditorium',
                'description' => 'Projector, sound system'
             ];
        }
        // === KONIEC PRZYKŁADOWYCH DANYCH ===

        // Jeśli nie znaleziono pokoju, możesz zwrócić 404
        if (!$roomDetails) {
            // Możesz stworzyć dedykowany widok 404 lub przekierować
            // Na razie prosty komunikat:
             die("Room with ID $roomId not found!"); 
             // Lepsza opcja: return $this->render('404');
        }

        // Przekaż dane do widoku i go wyrenderuj
        return $this->render('room_info', ['room' => $roomDetails]);
    }
}