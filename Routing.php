<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/ReservationController.php';
require_once 'src/controllers/BookingsController.php';
require_once 'src/controllers/RoomController.php';
require_once 'src/controllers/AboutController.php';
require_once 'src/controllers/AppController.php'; // WAŻNE!

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
                // Sprawdź czy $routeParts[$i] nie jest pusty przed dostępem do indeksu 0
                if (!empty($routeParts[$i]) && $routeParts[$i][0] === '{' && $routeParts[$i][strlen($routeParts[$i]) - 1] === '}') {
                    $paramName = trim($routeParts[$i], '{}');
                     // Sprawdź, czy $urlParts[$i] istnieje
                    if(isset($urlParts[$i])) {
                        $params[$paramName] = $urlParts[$i];
                    } else {
                        // Jeśli brakuje części URL dla parametru, to nie jest dopasowanie
                        $match = false;
                        break;
                    }
                } elseif (!isset($urlParts[$i]) || $routeParts[$i] !== $urlParts[$i]) { // Sprawdź czy $urlParts[$i] istnieje
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

                // Usuń pusty pierwszy element jeśli URL zaczynał się od /
                 if (isset($params[0]) && $params[0] === '') {
                     array_shift($params);
                 }
                
                // Użyj array_values, aby upewnić się, że przekazujemy indeksowaną tablicę
                return call_user_func_array([$object, $methodName], array_values($params));
            }
        }

        // Jeśli żadna trasa nie pasuje
        http_response_code(404);
        $controller = new AppController();
        return $controller->render('404');
    }
}