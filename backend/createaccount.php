<?php
header("Content-Type: application/json");
require "conection.php";

$data = json_decode(file_get_contents("php://input"), true);

// Recebendo dados corretamente
$email   = $data["email"]   ?? "";
$password = $data["password"] ?? "";
$cpf     = $data["cpf"]     ?? "";
$numero  = $data["numero"]  ?? "";
$nome = $data["nome"]  ?? "";
// Validação básica
if (!$email || !$password || !$cpf || !$numero) {
    echo json_encode([
        "success" => false,
        "msg" => "Preencha todos os campos, não foi possivel realizar o cadastro"
    ]);
    exit;
}
// Verifica se email já existe
$sqlm  = "SELECT id FROM users WHERE email = :email";
$stmtm = $pdo->prepare($sqlm);
$stmtm->bindParam(":email", $email);
$stmtm->execute();

if ($stmtm->rowCount() > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Email já cadastrado, não foi possivel realizar o cadastro"
    ]);
    exit;
}
$sqlc = "SELECT id FROM users WHERE cpf = :cpf";
$stmtc = $pdo->prepare($sqlc);
$stmtc->bindParam(":cpf", $cpf);
$stmtc->execute();

if ($stmtc->rowCount() > 0) {
    echo json_encode([
        "success" => false,
        "message" => "CPF já cadastrado, não foi possivel realizar o cadastro"
    ]);
    exit;
}
$sqln = "SELECT id FROM users WHERE numero = :numero";
$stmtn = $pdo->prepare($sqln);
$stmtn->bindParam(":numero", $numero);
$stmtn->execute();

if ($stmtn->rowCount() > 0) {
    echo json_encode([
        "success" => false,
        "message" => "numero de celular já cadastrado, não foi possivel realizar o cadastro"
    ]);
    exit;
}
// Criptografa a senha
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
// Insere usuário
$sql = "INSERT INTO users (nome, email, password, numero, cpf)
        VALUES (:nome, :email, :password, :numero, :cpf)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":email", $email);
$stmt->bindParam(":password", $hashedPassword);
$stmt->bindParam(":numero", $numero);
$stmt->bindParam(":cpf", $cpf);
$stmt->bindParam(":nome", $nome);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Erro ao criar conta, não foi possivel realizar o cadastro"
    ]);
}

exit;