<?php
$pdo = new PDO("mysql:host=db;dbname=crud", "user", "password");
header("Content-Type: application/json");

// --------------------------
// Fonctions réutilisables
// --------------------------

function formatUser($name, $email) {
    // Formatte les données utilisateur
    return [
        "name" => ucfirst($name),
        "email" => strtolower($email)
    ];
}


function insertUser(PDO $pdo, $name, $email) {
    $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->execute([$name, $email]);
    return $pdo->lastInsertId();
}

function getAllUsers(PDO $pdo) {
    $stmt = $pdo->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateUser(PDO $pdo, $id, $name, $email) {
    $stmt = $pdo->prepare("UPDATE users SET name=?, email=? WHERE id=?");
    return $stmt->execute([$name, $email, $id]);
}

function deleteUser(PDO $pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
    return $stmt->execute([$id]);
}

// --------------------------
// Routeur API HTTP
// --------------------------

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    echo json_encode(getAllUsers($pdo));
    break;

  case 'POST':
    $data = json_decode(file_get_contents("php://input"), true);
    $id = insertUser($pdo, $data['name'], $data['email']);
    echo json_encode(["id" => $id]);
    break;

  case 'PUT':
    $data = json_decode(file_get_contents("php://input"), true);
    $updated = updateUser($pdo, $data['id'], $data['name'], $data['email']);
    echo json_encode(["updated" => $updated]);
    break;

  case 'DELETE':
    parse_str(file_get_contents("php://input"), $data);
    $deleted = deleteUser($pdo, $data['id']);
    echo json_encode(["deleted" => $deleted]);
    break;
}
?>
