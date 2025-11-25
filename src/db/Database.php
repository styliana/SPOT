<?php

require_once __DIR__ . '/config.php'; 

class Database {
    private $username;
    private $password;
    private $host;
    private $database;
    // Dodajemy właściwość do trzymania połączenia
    private $connection = null;

    public function __construct()
    {
        $this->username = USERNAME;
        $this->password = PASSWORD;
        $this->host = HOST;
        $this->database = DATABASE;
    }

    public function connect()
    {
        // Jeśli połączenie już istnieje, zwracamy je (Singleton per request)
        if ($this->connection !== null) {
            return $this->connection;
        }

        try {
            $conn = new PDO(
                "pgsql:host=$this->host;port=5432;dbname=$this->database;sslmode=prefer",
                $this->username,
                $this->password
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Zapisujemy połączenie
            $this->connection = $conn;
            
            return $conn;
        }
        catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}