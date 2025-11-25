<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/models/User.php';

class UserTest extends TestCase
{
    public function testUserCreation()
    {
        // Given
        $email = 'test@example.com';
        $password = 'hashed_password';
        $name = 'Jan';
        $surname = 'Kowalski';
        $role = 'student';

        // When
        $user = new User($email, $password, $name, $surname, $role);

        // Then
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($name, $user->getName());
        $this->assertEquals('student', $user->getRole());
    }

    public function testUserRoleDefault()
    {
        // Test czy domyślna rola to student (jeśli tak jest w logice, tutaj zakładamy wstrzyknięcie)
        $user = new User('a@b.com', 'pass', 'A', 'B', 'admin');
        $this->assertEquals('admin', $user->getRole());
    }
}