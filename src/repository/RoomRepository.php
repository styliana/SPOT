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

    public function getRooms(): array {
        $result = [];
        $stmt = $this->database->connect()->prepare('SELECT * FROM rooms ORDER BY id');
        $stmt->execute();
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rooms as $room) {
            $result[] = new Room(
                $room['id'],
                $room['name'],
                $room['workspaces'],
                $room['type'],
                $room['description']
            );
        }
        return $result;
    }

    public function addRoom(Room $room): void {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO rooms (id, name, workspaces, type, description)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $room->getId(), $room->getName(), $room->getWorkspaces(), $room->getType(), $room->getDescription()
        ]);
    }

    public function deleteRoom(string $id): void {
        // Najpierw usuń rezerwacje na ten pokój
        $stmt = $this->database->connect()->prepare('DELETE FROM bookings WHERE room_id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();

        $stmt = $this->database->connect()->prepare('DELETE FROM rooms WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
    }
}