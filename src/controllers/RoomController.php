<?php

require_once 'AppController.php';

class RoomController extends AppController {

    public function room(string $roomId) {
        
        // === ZAKTUALIZOWANE DANE PRZYKŁADOWE ===
        // Dodaj tutaj dane dla WSZYSTKICH pokoi z Twojego SVG
        $allRoomsData = [
            'ROOM1' => ['id' => 'ROOM1', 'name' => 'Pokój ROOM1', 'workspaces' => 15, 'type' => 'Biuro', 'description' => 'Standardowe wyposażenie biurowe.'],
            'AULA1' => ['id' => 'AULA1', 'name' => 'Aula A1', 'workspaces' => 120, 'type' => 'Aula wykładowa', 'description' => 'Projektor, nagłośnienie.'],
            'ROOM2' => ['id' => 'ROOM2', 'name' => 'Pokój ROOM2', 'workspaces' => 8, 'type' => 'Sala seminaryjna', 'description' => 'Mała sala na spotkania.'],
            'ROOM3' => ['id' => 'ROOM3', 'name' => 'Pokój ROOM3', 'workspaces' => 20, 'type' => 'Laboratorium', 'description' => 'Wyposażenie laboratoryjne.'], // Ten jest 'unavailable', ale nadal może mieć stronę info
            'ROOM4' => ['id' => 'ROOM4', 'name' => 'Pokój ROOM4', 'workspaces' => 10, 'type' => 'Biuro', 'description' => 'Pokój dla pracowników.'],
            'ROOM5' => ['id' => 'ROOM5', 'name' => 'Pokój ROOM5', 'workspaces' => 30, 'type' => 'Sala komputerowa', 'description' => 'Stanowiska komputerowe.'],
            'STUDYROOM1' => ['id' => 'STUDYROOM1', 'name' => 'Pokój Cichej Nauki', 'workspaces' => 12, 'type' => 'Czytelnia', 'description' => 'Miejsce do nauki indywidualnej.'],
            'AULA2' => ['id' => 'AULA2', 'name' => 'Aula A2', 'workspaces' => 200, 'type' => 'Aula wykładowa', 'description' => 'Duża aula na parterze.'], // Ten też jest 'unavailable'
            // Dodaj resztę pokoi, jeśli masz więcej w SVG...
        ];

        // Sprawdzamy, czy mamy dane dla żądanego ID
        $roomDetails = $allRoomsData[$roomId] ?? null; 
        // === KONIEC AKTUALIZACJI DANYCH ===


        if (!$roomDetails) {
             // Zamiast die(), zwracamy widok 404
             http_response_code(404); 
             return $this->render('404');
            // die("Room with ID $roomId not found!"); // Stara wersja
        }

        return $this->render('room_info', ['room' => $roomDetails]);
    }
}