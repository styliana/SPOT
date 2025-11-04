<?php

/* --- GLOBALNA OBSŁUGA BŁĘDÓW KRYTYCZNYCH (500) --- */
set_exception_handler(function($exception) {
    http_response_code(500);
    error_log("Uncaught exception: " . $exception->getMessage() . "\n" . $exception->getTraceAsString());
    
    // Wyświetlamy użytkownikowi ładną stronę błędu 500
    if (file_exists('public/views/500.html')) {
        include 'public/views/500.html';
    } else {
        die("Wystąpił krytyczny błąd serwera.");
    }
});
/* --- KONIEC OBSŁUGI BŁĘDÓW 500 --- */
// wywolaj_nieistniejaca_funkcje_zeby_zobaczyc_blad_500();


// Wyłączamy pokazywanie błędów PHP użytkownikowi
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('login', 'SecurityController@login');
Routing::post('login', 'SecurityController@login');
Routing::get('register', 'SecurityController@register');
Routing::post('register', 'SecurityController@register');

Routing::get('reservation', 'ReservationController@reservation');
Routing::post('reservation', 'ReservationController@reservation');

Routing::get('mybookings', 'BookingsController@mybookings');

Routing::get('myprofile', 'ProfileController@myprofile');

Routing::get('about', 'AboutController@about');

Routing::get('room/{roomId}', 'RoomController@room');


Routing::run($path);