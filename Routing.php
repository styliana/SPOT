<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/ReservationController.php';
require_once 'src/controllers/BookingsController.php';
require_once 'src/controllers/RoomController.php';
require_once 'src/controllers/AboutController.php';
require_once 'src/controllers/ProfileController.php';
require_once 'src/controllers/AppController.php';


class Routing {

    private static $routes = [
        'GET' => [],
        'POST' => []
    ];

    public static function get(string $url, string $controllerAction) {
        self::$routes['GET'][$url] = $controllerAction;
    }

    public static function post(string $url, string $controllerAction) {
        self::$routes['POST'][$url] = $controllerAction;
    }

    public static function run(string $url) {
        $method = $_SERVER['REQUEST_METHOD'];

        $urlParts = explode('/', $url);
        $action = ($urlParts[0] === '' && isset($urlParts[1])) ? $urlParts[1] : ($urlParts[0] ?: 'login');
        if ($url === '' && $action === 'login') {
            $url = 'login';
            $urlParts = ['login'];
        } elseif ($urlParts[0] === '' && count($urlParts) > 1) {
            array_shift($urlParts);
            $url = implode('/', $urlParts);
        }

        foreach (self::$routes[$method] as $route => $controllerAction) {
            $routeParts = explode('/', $route);

            if (strpos($controllerAction, '@') === false) {
                 error_log("Invalid route definition: $controllerAction. Missing '@'.");
                 continue;
            }
            $controllerName = explode('@', $controllerAction)[0];
            $methodName = explode('@', $controllerAction)[1];

            if (count($urlParts) !== count($routeParts)) {
                continue;
            }

            $match = true;
            $params = [];
            for ($i = 0; $i < count($routeParts); $i++) {
                if (!isset($routeParts[$i]) || !isset($urlParts[$i])) {
                     $match = false;
                     break;
                }

                if ($routeParts[$i] !== '' && $routeParts[$i][0] === '{' && $routeParts[$i][strlen($routeParts[$i]) - 1] === '}') {
                    $paramName = trim($routeParts[$i], '{}');
                    $params[$paramName] = $urlParts[$i];
                } elseif ($routeParts[$i] !== $urlParts[$i]) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                if (!class_exists($controllerName)) {
                     error_log("Controller class not found: $controllerName");
                     continue;
                }
                $object = new $controllerName;

                if (!method_exists($object, $methodName)) {
                     error_log("Method not found in controller $controllerName: $methodName");
                     continue;
                }

                // Wywołanie metody kontrolera z parametrami
                return call_user_func_array([$object, $methodName], array_values($params));
            }
        }


        // Nie znaleziono trasy, wywołujemy publiczną metodę notFound()
        if (class_exists('AppController')) {
            $controller = new AppController();
            // Sprawdzamy, czy istnieje publiczna metoda notFound
            if (method_exists($controller, 'notFound')) {
                 return $controller->notFound(); // Wywołujemy publiczną metodę
            } else {
                 // Fallback, jeśli metoda notFound z jakiegoś powodu nie istnieje
                 http_response_code(404);
                 die("404 Not Found (AppController has no notFound method)");
            }
        } else {
             // Fallback, jeśli AppController nie jest załadowany
             http_response_code(404);
             die("404 Not Found (and AppController is missing)");
        }

    }
}