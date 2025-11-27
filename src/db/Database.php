<?php

require_once __DIR__ . '/config.php'; 

class Database {
    // 1. Statyczna właściwość przetrzymująca JEDYNĄ instancję tej klasy
    private static $instance = null;

    private $username;
    private $password;
    private $host;
    private $database;
    private $connection = null;

    // 2. Konstruktor musi być PRYWATNY, żeby nikt nie mógł zrobić "new Database()"
    private function __construct()
    {
        $this->username = USERNAME;
        $this->password = PASSWORD;
        $this->host = HOST;
        $this->database = DATABASE;
    }

    // 3. Statyczna metoda, która zwraca zawsze tę samą instancję
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function connect()
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        try {
            // Port 5432 jest standardowy wewnątrz sieci dockerowej (chyba że zmieniłeś go w configu kontenera db)
            $conn = new PDO(
                "pgsql:host=$this->host;port=5432;dbname=$this->database;sslmode=prefer",
                $this->username,
                $this->password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->connection = $conn;
            return $conn;
        }
        catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}