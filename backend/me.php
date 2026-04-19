<?php
session_start();
header("Content-Type: application/json");
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["logged" => false]);
    exit;
}
else {
    echo json_encode([
    "logged" => true,
    "user" => [
        "id" => $_SESSION["user_id"],
        "email" => $_SESSION["user_email"],
        "nome" => $_SESSION["user_nome"]
    ]
]);
exit;
}
