<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

// Security routes
Routing::get('login', 'SecurityController@login');
Routing::post('login', 'SecurityController@login');
Routing::get('register', 'SecurityController@register');
Routing::post('register', 'SecurityController@register');

// Reservation routes
Routing::get('reservation', 'ReservationController@reservation');
Routing::post('reservation', 'ReservationController@reservation');

// Bookings routes
Routing::get('mybookings', 'BookingsController@mybookings');

// Profile routes
Routing::get('myprofile', 'ProfileController@myprofile');

// About route
Routing::get('about', 'AboutController@about');

Routing::run($path);
