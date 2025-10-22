<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('login', 'SecurityController');
Routing::post('login', 'SecurityController');
Routing::get('register', 'SecurityController');
Routing::post('register', 'SecurityController');

Routing::run($path);
?>