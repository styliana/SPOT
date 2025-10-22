<?php

require_once 'AppController.php'; // Poprawnie!

class ReservationController extends AppController {

    // Użyjmy nazwy trasy 'reservation'
    public function reservation() { 
        if ($this->isGet()) {
            return $this->render('reservation');
        }

        if ($this->isPost()) {
            // ... logika POST ...
            return $this->render('reservation', ['message' => 'Rezerwacja dodana pomyślnie!']);
        }

        return $this->render('reservation');
    }
}