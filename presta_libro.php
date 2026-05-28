<?php
require_once 'connessione.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id_libro']) && !empty(trim($_POST['nome_utente'])) && !empty(trim($_POST['codice_ritiro']))) {

        $id_libro = (int) $_POST['id_libro'];
        $nome_utente = trim($_POST['nome_utente']);
        $codice_ritiro = trim($_POST['codice_ritiro']);

        try {
            $pdo->beginTransaction();

            $sql_prestito = "INSERT INTO prestito (id_libro, nome_utente, codice_ritiro) VALUES (?, ?, ?)";
            $stmt_prestito = $pdo->prepare($sql_prestito);
            $stmt_prestito->execute([$id_libro, $nome_utente, $codice_ritiro]);

            $sql_libro = "UPDATE libro SET stato = FALSE WHERE id_libro = ?";
            $stmt_libro = $pdo->prepare($sql_libro);
            $stmt_libro->execute([$id_libro]);

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
        }
    }
}

header('Location: index.php');
exit;