<?php

session_start();

/* --- GLOBALNA OBSŁUGA BŁĘDÓW (500) --- */
set_exception_handler(function($exception) {
    http_response_code(500);
    error_log("Uncaught exception: " . $exception->getMessage() . "\n" . $exception->getTraceAsString());
    
    if (file_exists('public/views/500.html')) {
        include 'public/views/500.html';
    } else {
        die("Wystąpił krytyczny błąd serwera.");
    }
});

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

// --- DEFINICJA TRAS ---
Routing::get('login', 'SecurityController@login');
Routing::post('login', 'SecurityController@login');
Routing::get('register', 'SecurityController@register');
Routing::post('register', 'SecurityController@register');
Routing::get('logout', 'SecurityController@logout');

Routing::get('reservation', 'ReservationController@reservation');
Routing::post('reservation', 'ReservationController@reservation');

Routing::post('api/checkAvailability', 'ReservationController@checkAvailability');

Routing::get('mybookings', 'BookingsController@mybookings');
Routing::get('myprofile', 'ProfileController@myprofile');
Routing::get('about', 'AboutController@about');
Routing::get('room/{roomId}', 'RoomController@room');


Routing::get('users', 'UserController@users');

Routing::run($path);