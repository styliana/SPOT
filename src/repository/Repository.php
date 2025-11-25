<?php

require_once __DIR__ . '/../db/Database.php';

class Repository {
    protected $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    // === TRANSAKCJE ===
    public function beginTransaction() {
        $this->database->connect()->beginTransaction();
    }

    public function commit() {
        $this->database->connect()->commit();
    }

    public function rollBack() {
        $this->database->connect()->rollBack();
    }
}