<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/RoomRepository.php';

class RoomController extends AppController {

    private $roomRepository;

    public function __construct()
    {
        parent::__construct();
        $this->roomRepository = new RoomRepository();
    }

    public function room(string $roomId) {
        
        // Pobieramy dane z bazy
        $room = $this->roomRepository->getRoom($roomId);

        if (!$room) {
             http_response_code(404); 
             return $this->render('404');
        }

        // Przekazujemy obiekt Room do widoku. 
        // Uwaga: w widoku roominfo.php musimy teraz używać metod (np. $room->getName()) zamiast tablicy ($room['name'])!
        // DLA UŁATWIENIA: Przekonwertuję to na tablicę tutaj, żebyś nie musiał zmieniać widoku.
        
        $roomData = [
            'id' => $room->getId(),
            'name' => $room->getName(),
            'workspaces' => $room->getWorkspaces(),
            'type' => $room->getType(),
            'description' => $room->getDescription()
        ];

        return $this->render('room_info', ['room' => $roomData]);
    }
}