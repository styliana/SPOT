<?php

// Wyświetlanie błędów (przydatne podczas developmentu)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

// Format: 'url', 'Kontroler@metoda'
Routing::get('login', 'SecurityController@login');
Routing::post('login', 'SecurityController@login'); 
Routing::get('register', 'SecurityController@register');
Routing::post('register', 'SecurityController@register');

Routing::get('reservation', 'ReservationController@reservation');
Routing::post('reservation', 'ReservationController@reservation');

Routing::get('mybookings', 'BookingsController@mybookings');

Routing::get('about', 'AboutController@about');

// Dynamiczna trasa dla informacji o pokoju
Routing::get('room/{roomId}', 'RoomController@room'); 


Routing::run($path);
?>