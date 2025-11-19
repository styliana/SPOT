<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Booking.php';

class BookingRepository extends Repository {

    public function getBookingsByUserId(int $userId): array {
        $result = [];
        
        // Łączymy tabelę bookings z rooms, żeby pobrać nazwę i typ pokoju
        $stmt = $this->database->connect()->prepare('
            SELECT b.*, r.name as room_name, r.type as room_type
            FROM bookings b
            LEFT JOIN rooms r ON b.room_id = r.id
            WHERE b.user_id = :user_id
            ORDER BY b.date ASC, b.start_time ASC
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
}