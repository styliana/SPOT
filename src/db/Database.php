<?php

// === POPRAWKA: Używamy __DIR__ aby wskazać na ten sam katalog ===
require_once __DIR__ . '/config.php'; 

class Database {
    private $username;
    private $password;
    private $host;
    private $database;

    public function __construct()
    {
        $this->username = USERNAME;
        $this->password = PASSWORD;
        $this->host = HOST;
        $this->database = DATABASE;
    }

    public function connect()
    {
        try {
            // Drobna poprawka: sslmode=prefer jest bezpieczniejszy
            $conn = new PDO(
                "pgsql:host=$this->host;port=5432;dbname=$this->database;sslmode=prefer",
                $this->username,
                $this->password
            );

            // Ustawiamy tryb błędów na wyjątki (ważne dla debugowania!)
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $conn;
        }
        catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}