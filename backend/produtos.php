<?php
require "conection.php";

$stmt = $pdo->query("SELECT * FROM products");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($produtos);


?>