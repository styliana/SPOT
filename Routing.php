<?php

// Te ścieżki są poprawne, bo są liczone od /app/
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/ReservationController.php';

class Routing {

    private static $routes;

    public static function get($url, $controller) {
        self::$routes[$url] = $controller;
    }

    public static function post($url, $controller) {
        self::$routes[$url] = $controller;
    }

    public static function run($url) {
        $action = explode("/", $url)[0];

        if (empty($action)) {
            $action = 'login'; // Domyślna akcja
        }

        if (!array_key_exists($action, self::$routes)) {
            die("Wrong url! Action: " . $action);
        }

        $controllerName = self::$routes[$action];
        $object = new $controllerName;

        // Sprawdzamy, czy kontroler ma metodę o nazwie akcji
        if (method_exists($object, $action)) {
            return $object->$action();
        } else {
            die("Method $action not found in controller $controllerName!");
        }
    }
}