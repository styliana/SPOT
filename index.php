<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

// Format: 'url', 'Kontroler@metoda'
Routing::get('login', 'SecurityController@login');
Routing::post('login', 'SecurityController@login'); // Zakładam, że login obsługuje też POST
Routing::get('register', 'SecurityController@register');
Routing::post('register', 'SecurityController@register');

Routing::get('reservation', 'ReservationController@reservation');
Routing::post('reservation', 'ReservationController@reservation');

Routing::get('mybookings', 'BookingsController@mybookings');

// {roomId} zostanie przekazane jako argument do metody 'room' w RoomController
Routing::get('room/{roomId}', 'RoomController@room'); 

Routing::get('about', 'AboutController@about');



Routing::run($path);
?>