<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Booking.php';

class BookingRepository extends Repository {

    public function getBookingsByUserId(int $userId): array {
        $result = [];
        $stmt = $this->database->connect()->prepare('
            SELECT b.*, r.name as room_name, r.type as room_type
            FROM bookings b
            LEFT JOIN rooms r ON b.room_id = r.id
            WHERE b.user_id = :user_id
            ORDER BY b.date DESC, b.start_time ASC
        ');
        
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($bookings as $booking) {
            $result[] = new Booking(
                $booking['id'],
                $booking['user_id'],
                $booking['room_id'],
                $booking['room_name'] ?? 'Nieznany pokój',
                $booking['room_type'] ?? 'Inne',
                $booking['date'],
                $booking['start_time'],
                $booking['end_time'],
                $booking['status']
            );
        }
        return $result;
    }

    public function addBooking(int $userId, string $roomId, string $date, string $startTime, string $endTime): void {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO bookings (user_id, room_id, date, start_time, end_time, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$userId, $roomId, $date, $startTime, $endTime, 'Confirmed']);
    }

    public function updateBooking(int $bookingId, int $userId, string $roomId, string $date, string $startTime, string $endTime): void {
        $stmt = $this->database->connect()->prepare('
            UPDATE bookings 
            SET room_id = ?, date = ?, start_time = ?, end_time = ?
            WHERE id = ? AND user_id = ?
        ');
        $stmt->execute([$roomId, $date, $startTime, $endTime, $bookingId, $userId]);
    }

    public function deleteBooking(int $id): void {
        $stmt = $this->database->connect()->prepare('
            DELETE FROM bookings WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // === ZMODYFIKOWANA METODA ===
    // Dodano parametr $excludeBookingId (domyślnie null)
    public function getBookedRoomIds(string $date, string $startTime, string $endTime, ?int $excludeBookingId = null): array {
        
        $sql = 'SELECT room_id FROM bookings
                WHERE date = :date
                AND ((start_time < :end_time) AND (end_time > :start_time))
                AND status != \'Cancelled\'';

        // Jeśli edytujemy, dodajemy warunek: "oprócz tej konkretnej rezerwacji"
        if ($excludeBookingId) {
            $sql .= ' AND id != :exclude_id';
        }

        $stmt = $this->database->connect()->prepare($sql);

        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':start_time', $startTime, PDO::PARAM_STR);
        $stmt->bindParam(':end_time', $endTime, PDO::PARAM_STR);
        
        if ($excludeBookingId) {
            $stmt->bindParam(':exclude_id', $excludeBookingId, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}