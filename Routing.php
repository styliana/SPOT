<?php

require_once __DIR__ . '/src/controllers/AppController.php';
require_once __DIR__ . '/src/db/Database.php';
require_once __DIR__ . '/src/models/User.php';
require_once __DIR__ . '/src/models/Room.php';
require_once __DIR__ . '/src/models/Booking.php';

require_once __DIR__ . '/src/repository/Repository.php';
require_once __DIR__ . '/src/repository/UserRepository.php';
require_once __DIR__ . '/src/repository/RoomRepository.php';
require_once __DIR__ . '/src/repository/BookingRepository.php';

require_once __DIR__ . '/src/controllers/SecurityController.php';
require_once __DIR__ . '/src/controllers/ReservationController.php';
require_once __DIR__ . '/src/controllers/BookingsController.php';
require_once __DIR__ . '/src/controllers/RoomController.php';
require_once __DIR__ . '/src/controllers/AboutController.php';
require_once __DIR__ . '/src/controllers/ProfileController.php';
require_once __DIR__ . '/src/controllers/UserController.php';
require_once __DIR__ . '/src/controllers/AdminController.php';

class Routing {

    private static $routes = [ 'GET' => [], 'POST' => [] ];

    public static function get($url, $action) { self::$routes['GET'][$url] = $action; }
    public static function post($url, $action) { self::$routes['POST'][$url] = $action; }

    public static function run($url) {
        $method = $_SERVER['REQUEST_METHOD'];
        $urlParts = explode('/', $url);
        $action = ($urlParts[0] === '' && isset($urlParts[1])) ? $urlParts[1] : ($urlParts[0] ?: 'login');
        
        if ($url === '' && $action === 'login') { $url = 'login'; $urlParts = ['login']; }
        elseif ($urlParts[0] === '' && count($urlParts) > 1) { array_shift($urlParts); $url = implode('/', $urlParts); }

        foreach (self::$routes[$method] as $route => $controllerAction) {
            $routeParts = explode('/', $route);
            if (strpos($controllerAction, '@') === false) continue;
            
            $controllerName = explode('@', $controllerAction)[0];
            $methodName = explode('@', $controllerAction)[1];

            if (count($urlParts) !== count($routeParts)) continue;

            $match = true;
            $params = [];
            for ($i = 0; $i < count($routeParts); $i++) {
                if (!isset($routeParts[$i]) || !isset($urlParts[$i])) { $match = false; break; }
                if ($routeParts[$i] !== '' && strpos($routeParts[$i], '{') !== false) {
                    $params[trim($routeParts[$i], '{}')] = $urlParts[$i];
                } elseif ($routeParts[$i] !== $urlParts[$i]) { $match = false; break; }
            }

            if ($match) {
                if (!class_exists($controllerName)) { error_log("Class $controllerName not found"); continue; }
                $object = new $controllerName;
                if (!method_exists($object, $methodName)) { error_log("Method $methodName not found"); continue; }
                return call_user_func_array([$object, $methodName], array_values($params));
            }
        }
        
        if (class_exists('AppController')) { (new AppController())->notFound(); }
        else { http_response_code(404); }
    }
}