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
        
        $room = $this->roomRepository->getRoom($roomId);

        if (!$room) {
             http_response_code(404); 
             return $this->render('404');
        }

        $roomData = [
            'id' => $room->getId(),
            'name' => $room->getName(),
            'workspaces' => $room->getWorkspaces(),
            'type' => $room->getType(),
            'description' => $room->getDescription()
        ];

        return $this->render('roominfo', ['room' => $roomData]);
    }
}