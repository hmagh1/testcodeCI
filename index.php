<?php

function connectDB(): PDO {
    return new PDO("mysql:host=db;dbname=crud", "user", "password");
}

function getAllUsers(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertUser(PDO $pdo, string $name, string $email): int {
    $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->execute([$name, $email]);
    return (int)$pdo->lastInsertId();
}

function updateUser(PDO $pdo, int $id, string $name, string $email): bool {
    $stmt = $pdo->prepare("UPDATE users SET name=?, email=? WHERE id=?");
    return $stmt->execute([$name, $email, $id]);
}

function deleteUser(PDO $pdo, int $id): bool {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
    return $stmt->execute([$id]);
}

function formatUser(string $name, string $email): array {
    return [
        "name" => ucfirst($name),
        "email" => strtolower($email)
    ];
}

function handleRequest(PDO $pdo): void {
    header("Content-Type: application/json");
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            echo json_encode(getAllUsers($pdo));
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            echo json_encode(["id" => insertUser($pdo, $data['name'], $data['email'])]);
            break;
        case 'PUT':
            $data = json_decode(file_get_contents("php://input"), true);
            echo json_encode(["updated" => updateUser($pdo, $data['id'], $data['name'], $data['email'])]);
            break;
        case 'DELETE':
            parse_str(file_get_contents("php://input"), $data);
            echo json_encode(["deleted" => deleteUser($pdo, $data['id'])]);
            break;
    }
}

// Ne s'exécute que pour les requêtes HTTP, pas en test CLI
if (php_sapi_name() !== 'cli') {
    $pdo = connectDB();
    handleRequest($pdo);
}
