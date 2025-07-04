<?php
$pdo = new PDO("mysql:host=db;dbname=crud", "user", "password");
header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    $stmt = $pdo->query("SELECT * FROM users");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    break;
  case 'POST':
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->execute([$data['name'], $data['email']]);
    echo json_encode(["id" => $pdo->lastInsertId()]);
    break;
  case 'PUT':
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("UPDATE users SET name=?, email=? WHERE id=?");
    $stmt->execute([$data['name'], $data['email'], $data['id']]);
    echo json_encode(["updated" => true]);
    break;
  
}
?>
