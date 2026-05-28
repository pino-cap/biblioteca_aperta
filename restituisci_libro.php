<?php
require_once 'connessione.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id_libro']) && !empty(trim($_POST['codice_ritiro']))) {

        $id_libro = (int) $_POST['id_libro'];
        $codice_inserito = trim($_POST['codice_ritiro']);

        $stmt_check = $pdo->prepare("SELECT codice_ritiro FROM prestito WHERE id_libro = ?");
        $stmt_check->execute([$id_libro]);
        $prestito = $stmt_check->fetch();

        if ($prestito && $prestito['codice_ritiro'] === $codice_inserito) {
            try {
                $pdo->beginTransaction();

                $sql_libro = "UPDATE libro SET stato = TRUE WHERE id_libro = ?";
                $stmt_libro = $pdo->prepare($sql_libro);
                $stmt_libro->execute([$id_libro]);

                $sql_prestito = "DELETE FROM prestito WHERE id_libro = ?";
                $stmt_prestito = $pdo->prepare($sql_prestito);
                $stmt_prestito->execute([$id_libro]);

                $pdo->commit();

                header('Location: index.php');
                exit;

            } catch (Exception $e) {
                $pdo->rollBack();
                die("Errore del database. <a href='index.php'>Torna indietro</a>");
            }
        } else {
            die("Codice di ritiro errato! Non puoi restituire questo libro. <br><br> <a href='index.php'>Torna indietro</a>");
        }
    }
}

header('Location: index.php');
exit;