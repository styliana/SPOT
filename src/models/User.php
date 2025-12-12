<?php

class User {
    private $email;
    private $password;
    private $name;
    private $surname;
    private $role; 
    private $id;

    public function __construct(
        string $email, 
        string $password, 
        string $name, 
        string $surname,
        string $role = 'student',
        int $id = null
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->surname = $surname;
        $this->role = $role;
        $this->id = $id;
    }

    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getName(): string { return $this->name; }
    public function getSurname(): string { return $this->surname; }
    public function getRole(): string { return $this->role; }
    public function getId(): ?int { return $this->id; }
}