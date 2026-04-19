<?php
header("Content-Type: application/json");
require "conection.php";
session_start();
$data = json_decode(file_get_contents("php://input"), true);
if (isset($_SESSION["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Usuário já está logado"
    ]);
    exit;
}
$email   = $data["emailinput"]   ?? "";
$password = $data["senhainput"] ?? "";


if (!$email || !$password) {
    echo json_encode([
        "success" => false,
        "message" => "Preencha todos os campos"
    ]);
    exit;
}
// Busca usuário
$sql = "SELECT id, email, password, nome FROM users WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":email", $email);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode([
        "success" => false,
        "message" => "Usuário não encontrado"
    ]);
    exit;
}

// Verifica senha
if (!password_verify($password, $user["password"])) {
    echo json_encode([
        "success" => false,
        "message" => "Senha incorreta"
    ]);
    exit;
}
else {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["user_email"] = $user["email"];
    $_SESSION["user_nome"] = $user["nome"];
    echo json_encode([
        "success" => true,
        "message" => "Login realizado"
    ]);
}
// LOGIN OK → cria sessão

exit;