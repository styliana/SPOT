<?php

session_start();

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

// --- TRASY PUBLICZNE ---
Routing::get('login', 'SecurityController@login');
Routing::post('login', 'SecurityController@login');
Routing::get('register', 'SecurityController@register');
Routing::post('register', 'SecurityController@register');
Routing::get('logout', 'SecurityController@logout');

// --- TRASY UŻYTKOWNIKA ---
Routing::get('reservation', 'ReservationController@reservation');
Routing::post('reservation', 'ReservationController@reservation');
Routing::post('api/checkAvailability', 'ReservationController@checkAvailability');

Routing::get('mybookings', 'BookingsController@mybookings');
Routing::post('delete_booking', 'BookingsController@delete');

Routing::get('myprofile', 'ProfileController@myprofile');
Routing::get('edit_profile', 'ProfileController@edit');
Routing::post('edit_profile', 'ProfileController@update');

Routing::get('about', 'AboutController@about');
Routing::get('room/{roomId}', 'RoomController@room');

// TRASY ADMINA
Routing::get('admin_users', 'AdminController@admin_users');
Routing::get('admin_rooms', 'AdminController@admin_rooms');
Routing::post('admin_rooms', 'AdminController@admin_rooms'); 
Routing::get('admin_bookings', 'AdminController@admin_bookings');

Routing::post('admin_delete_user', 'AdminController@admin_delete_user');
Routing::post('admin_delete_room', 'AdminController@admin_delete_room');
Routing::post('admin_delete_booking', 'AdminController@admin_delete_booking');
Routing::post('admin_change_role', 'AdminController@admin_change_role');

Routing::get('admin_edit_user', 'AdminController@admin_edit_user');
Routing::post('admin_update_user', 'AdminController@admin_update_user');

Routing::run($path);