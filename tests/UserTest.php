<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../index.php';

final class UserTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=crud", "user", "password");
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100),
            email VARCHAR(100)
        )");
        $this->pdo->exec("DELETE FROM users"); // Clean before test
    }

    public function testInsertUser(): void
    {
        $id = insertUser($this->pdo, "Test", "test@example.com");
        $this->assertIsNumeric($id);
    }

    public function testGetAllUsers(): void
    {
        insertUser($this->pdo, "A", "a@test.com");
        $users = getAllUsers($this->pdo);
        $this->assertGreaterThan(0, count($users));
    }

    public function testUpdateUser(): void
    {
        $id = insertUser($this->pdo, "Old", "old@test.com");
        $success = updateUser($this->pdo, $id, "New", "new@test.com");
        $this->assertTrue($success);
    }

    public function testDeleteUser(): void
    {
        $id = insertUser($this->pdo, "ToDelete", "delete@test.com");
        $success = deleteUser($this->pdo, $id);
        $this->assertTrue($success);
    }
}
