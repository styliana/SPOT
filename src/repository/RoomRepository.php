<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Room.php';

class RoomRepository extends Repository {

    public function getRoom(string $id): ?Room {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM public.rooms WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();

        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($room == false) {
            return null;
        }

        return new Room(
            $room['id'],
            $room['name'],
            $room['workspaces'],
            $room['type'],
            $room['description']
        );
    }
}