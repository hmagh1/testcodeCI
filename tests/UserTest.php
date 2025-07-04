<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../index.php';

final class UserTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO("mysql:host=127.0.0.1;dbname=crud", "root", "root");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100),
            email VARCHAR(100)
        )");
        $this->pdo->exec("DELETE FROM users");
    }

    public function testInsertUser(): void
    {
        $id = insertUser($this->pdo, "Test", "test@example.com");
        $this->assertIsNumeric($id);
    }

    public function testGetAllUsers(): void
    {
        insertUser($this->pdo, "Alice", "alice@test.com");
        $users = getAllUsers($this->pdo);
        $this->assertGreaterThan(0, count($users));
        $this->assertEquals("Alice", $users[0]["name"]);
    }

    public function testUpdateUser(): void
    {
        $id = insertUser($this->pdo, "Old", "old@example.com");
        $success = updateUser($this->pdo, $id, "New", "new@example.com");
        $this->assertTrue($success);
        $users = getAllUsers($this->pdo);
        $this->assertEquals("New", $users[0]["name"]);
    }

    public function testDeleteUser(): void
    {
        $id = insertUser($this->pdo, "ToDelete", "delete@example.com");
        $success = deleteUser($this->pdo, $id);
        $this->assertTrue($success);
        $users = getAllUsers($this->pdo);
        $this->assertCount(0, $users);
    }

    public function testFormatUser(): void
    {
        $user = formatUser("moad", "EXAMPLE@EMAIL.COM");
        $this->assertEquals("Moad", $user["name"]);
        $this->assertEquals("example@email.com", $user["email"]);
    }


}
