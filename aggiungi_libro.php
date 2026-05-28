<?php
require_once 'connessione.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty(trim($_POST['isbn'])) && !empty(trim($_POST['titolo'])) && !empty(trim($_POST['autore'])) && !empty($_POST['id_categoria'])) {

        $isbn = trim($_POST['isbn']);
        $titolo = trim($_POST['titolo']);
        $autore = trim($_POST['autore']);
        $id_categoria = (int) $_POST['id_categoria'];

        $sql = "INSERT INTO libro (isbn, titolo, autore, id_categoria) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$isbn, $titolo, $autore, $id_categoria]);
    }
}

header('Location: index.php');
exit;