<?php

// === 1. KLASY BAZOWE I KONFIGURACJA ===
// Używamy __DIR__, aby mieć pewność, że ścieżki są poprawne niezależnie od miejsca wywołania
require_once __DIR__ . '/src/controllers/AppController.php';
require_once __DIR__ . '/src/db/Database.php';

// === 2. REPOZYTORIA ===
require_once __DIR__ . '/src/repository/Repository.php';
require_once __DIR__ . '/src/repository/UserRepository.php';

// === 3. KONTROLERY ===
require_once __DIR__ . '/src/controllers/SecurityController.php';
require_once __DIR__ . '/src/controllers/ReservationController.php';
require_once __DIR__ . '/src/controllers/BookingsController.php';
require_once __DIR__ . '/src/controllers/RoomController.php';
require_once __DIR__ . '/src/controllers/AboutController.php';
require_once __DIR__ . '/src/controllers/ProfileController.php';
require_once __DIR__ . '/src/controllers/UserController.php'; 


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
        
        // Obsługa pustego adresu (przekierowanie na login)
        if ($url === '' && $action === 'login') {
            $url = 'login';
            $urlParts = ['login'];
        } elseif ($urlParts[0] === '' && count($urlParts) > 1) {
            array_shift($urlParts);
            $url = implode('/', $urlParts);
        }

        // Pętla po zdefiniowanych trasach
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

            // Sprawdzanie dopasowania URL do trasy
            for ($i = 0; $i < count($routeParts); $i++) {
                if (!isset($routeParts[$i]) || !isset($urlParts[$i])) {
                     $match = false;
                     break;
                }

                // Obsługa parametrów dynamicznych np. {roomId}
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

        // Obsługa błędu 404 (Nie znaleziono)
        if (class_exists('AppController')) {
            $controller = new AppController();
            if (method_exists($controller, 'notFound')) {
                 return $controller->notFound();
            } else {
                 http_response_code(404);
                 die("404 Not Found (AppController has no notFound method)");
            }
        } else {
             http_response_code(404);
             die("404 Not Found (and AppController is missing)");
        }
    }
}