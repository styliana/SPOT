<?php

class Booking {
    private $id;
    private $userId;
    private $roomId;
    private $roomName;
    private $roomType;
    private $date;
    private $startTime;
    private $endTime;
    private $status;

    public function __construct(
        int $id, int $userId, string $roomId, string $roomName, string $roomType,
        string $date, string $startTime, string $endTime, string $status
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->roomId = $roomId;
        $this->roomName = $roomName;
        $this->roomType = $roomType;
        $this->date = $date;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    
    // === TEJ METODY BRAKOWAŁO ===
    public function getRoomId(): string { return $this->roomId; }
    // ============================

    public function getRoomName(): string { return $this->roomName; }
    public function getRoomType(): string { return $this->roomType; }
    public function getDate(): string { return $this->date; }
    
    public function getTimeRange(): string { 
        // Formatowanie czasu (usuwa sekundy)
        return substr($this->startTime, 0, 5) . ' - ' . substr($this->endTime, 0, 5); 
    }
    
    public function getStatus(): string { return $this->status; }
}