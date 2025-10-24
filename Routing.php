<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/ReservationController.php';
require_once 'src/controllers/BookingsController.php';
require_once 'src/controllers/RoomController.php'; 
require_once 'src/controllers/AboutController.php';

class Routing {

    // Zmieniamy na tablicę asocjacyjną dla lepszej organizacji
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
        $method = $_SERVER['REQUEST_METHOD']; // GET lub POST

        // Dzielimy URL na części
        $urlParts = explode('/', $url);
        $action = $urlParts[0] ?: 'login'; // Domyślna akcja to login

        // Szukamy pasującej trasy
        foreach (self::$routes[$method] as $route => $controllerAction) {
            $routeParts = explode('/', $route);
            $controllerName = explode('@', $controllerAction)[0];
            $methodName = explode('@', $controllerAction)[1];

            // Proste dopasowanie (np. /login)
            if (count($urlParts) === count($routeParts) && $route === $url) {
                $object = new $controllerName;
                return $object->$methodName();
            }

            // Dopasowanie dynamiczne (np. /room/{id})
            if (count($urlParts) === count($routeParts) && strpos($route, '{') !== false) {
                 $match = true;
                 $params = [];
                 for ($i = 0; $i < count($routeParts); $i++) {
                    if (strpos($routeParts[$i], '{') === 0 && strpos($routeParts[$i], '}') === strlen($routeParts[$i]) - 1) {
                        // To jest parametr dynamiczny, zapisujemy jego wartość
                        $paramName = trim($routeParts[$i], '{}');
                        $params[$paramName] = $urlParts[$i];
                    } elseif ($routeParts[$i] !== $urlParts[$i]) {
                        // Statyczna część URL się nie zgadza
                        $match = false;
                        break;
                    }
                 }

                 if ($match) {
                    $object = new $controllerName;
                    // Wywołujemy metodę kontrolera z parametrami
                    // Używamy call_user_func_array, aby przekazać parametry jako argumenty
                    return call_user_func_array([$object, $methodName], array_values($params));
                 }
            }
        }

        // Jeśli żadna trasa nie pasuje
        http_response_code(404); // Ustaw kod odpowiedzi na 404
        // Możesz tu wyrenderować ładną stronę 404
        $controller = new AppController(); // Potrzebujemy obiektu do renderowania
        return $controller->render('404'); 
        // Lub die("Wrong url or method! Action: " . $action . " Method: " . $method);
    }
}