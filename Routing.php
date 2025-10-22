<?php

require_once 'src/controllers/SecurityController.php';

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
            $action = 'login'; // Default action when URL is empty
        }
        
        if (!array_key_exists($action, self::$routes)) {
            die("Wrong url!");
        }

        $controller = self::$routes[$action];
        $object = new $controller;
        return $object->$action();
    }
}