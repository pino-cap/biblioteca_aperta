<?php
require_once 'connessione.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['nome_categoria']))) {
    $nome = trim($_POST['nome_categoria']);
    $stmt = $pdo->prepare("INSERT INTO categoria (nome_categoria) VALUES (?)");
    $stmt->execute([$nome]);
}

header('Location: index.php');
exit;