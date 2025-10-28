<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/ReservationController.php';
require_once 'src/controllers/BookingsController.php';
require_once 'src/controllers/RoomController.php';
require_once 'src/controllers/AboutController.php';
require_once 'src/controllers/AppController.php'; // WAŻNE: Dodano AppController

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

        // Usuń pierwszy pusty element, jeśli URL zaczynał się od /
        $urlParts = explode('/', $url);
        // Jeśli URL był pusty lub tylko '/', $action będzie pusty, ustawiamy na 'login'
        $action = $urlParts[0] ?: 'login';
        // Zaktualizuj URL po potencjalnym usunięciu pustego elementu (jeśli był tylko /)
        if ($url === '' && $action === 'login') {
            $url = 'login';
            $urlParts = ['login'];
        }


        foreach (self::$routes[$method] as $route => $controllerAction) {
            $routeParts = explode('/', $route);

            // Podstawowe sprawdzenie formatu
            if (strpos($controllerAction, '@') === false) {
                 error_log("Invalid route definition: $controllerAction. Missing '@'.");
                 continue;
            }
            $controllerName = explode('@', $controllerAction)[0];
            $methodName = explode('@', $controllerAction)[1];

            // Sprawdzanie dopasowania długości
            if (count($urlParts) !== count($routeParts)) {
                continue;
            }

            $match = true;
            $params = [];
            for ($i = 0; $i < count($routeParts); $i++) {
                // Sprawdzenie czy $routeParts[$i] i $urlParts[$i] istnieją
                if (!isset($routeParts[$i]) || !isset($urlParts[$i])) {
                     $match = false;
                     break;
                }

                // Sprawdzenie czy to parametr dynamiczny
                if ($routeParts[$i] !== '' && $routeParts[$i][0] === '{' && $routeParts[$i][strlen($routeParts[$i]) - 1] === '}') {
                    $paramName = trim($routeParts[$i], '{}');
                    $params[$paramName] = $urlParts[$i];
                } elseif ($routeParts[$i] !== $urlParts[$i]) {
                    // Statyczna część się nie zgadza
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

                // Przekazanie parametrów jako argumenty funkcji
                // Używamy array_values, aby upewnić się, że argumenty są indeksowane numerycznie
                return call_user_func_array([$object, $methodName], array_values($params));
            }
        }

        // Nie znaleziono trasy, zwracamy 404
        http_response_code(404);
        // Upewnij się, że klasa AppController jest dostępna (dzięki require_once na górze)
        if (class_exists('AppController')) {
            $controller = new AppController();
            // Sprawdź, czy metoda render istnieje, zanim ją wywołasz
            if (method_exists($controller, 'render')) {
                 return $controller->render('404');
            } else {
                 die("404 Not Found (AppController has no render method)");
            }
        } else {
            // Fallback, jeśli AppController z jakiegoś powodu nie jest załadowany
             die("404 Not Found (and AppController is missing)");
        }
    }
}
