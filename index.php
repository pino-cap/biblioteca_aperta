<?php
require_once 'connessione.php';

$stmt_cat = $pdo->query("SELECT * FROM categoria");
$categorie = $stmt_cat->fetchAll();

$sql = "SELECT libro.id_libro, libro.isbn, libro.titolo, libro.autore, libro.stato, categoria.nome_categoria 
        FROM libro 
        LEFT JOIN categoria ON libro.id_categoria = categoria.id_categoria";
$stmt = $pdo->query($sql);
$libri = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Lista dei Libri</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Tutti i Libri</h1>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ISBN</th>
            <th>Titolo</th>
            <th>Autore</th>
            <th>Categoria</th>
            <th>Stato</th>
            <th>Azioni</th>
        </tr>
        <?php foreach ($libri as $libro): ?>
            <tr>
                <td><?= htmlspecialchars($libro['isbn']) ?></td>
                <td><?= htmlspecialchars($libro['titolo']) ?></td>
                <td><?= htmlspecialchars($libro['autore']) ?></td>
                <td><?= htmlspecialchars($libro['nome_categoria'] ?? '') ?></td>
                <td><?= $libro['stato'] ? 'Disponibile' : 'In prestito' ?></td>
                <td>
                    <a href="elimina_libro.php?id=<?= $libro['id_libro'] ?>" onclick="return confirm('Sei sicuro?')"
                        style="color:red;">[Elimina]</a>

                    <span style="margin: 0 10px;">|</span>

                    <?php if ($libro['stato']): ?>
                        <form action="presta_libro.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id_libro" value="<?= $libro['id_libro'] ?>">
                            <input type="text" name="nome_utente" placeholder="Utente" required size="10">
                            <input type="text" name="codice_ritiro" placeholder="Codice" required size="6">
                            <button type="submit">[Presta]</button>
                        </form>
                    <?php else: ?>
                        <form action="restituisci_libro.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id_libro" value="<?= $libro['id_libro'] ?>">
                            <input type="text" name="codice_ritiro" placeholder="Inserisci Codice" required size="12">
                            <button type="submit">[Restituisci]</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <hr>

    <h2>Gestione Libri</h2>
    <form action="aggiungi_libro.php" method="POST" style="margin-bottom: 15px;">
        <input type="text" name="isbn" placeholder="ISBN" required>
        <input type="text" name="titolo" placeholder="Titolo" required>
        <input type="text" name="autore" placeholder="Autore" required>
        <select name="id_categoria" required>
            <option value="">Seleziona Categoria</option>
            <?php foreach ($categorie as $cat): ?>
                <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nome_categoria']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Aggiungi Libro</button>
    </form>

    <hr>

    <h2>Gestione Categorie</h2>

    <form action="aggiungi_categoria.php" method="POST" style="margin-bottom: 15px;">
        <input type="text" name="nome_categoria" placeholder="Nuova categoria" required>
        <button type="submit">Aggiungi</button>
    </form>

    <ul>
        <?php foreach ($categorie as $cat): ?>
            <li>
                <?= htmlspecialchars($cat['nome_categoria']) ?>
                <a href="elimina_categoria.php?id=<?= $cat['id_categoria'] ?>" onclick="return confirm('Sei sicuro?')"
                    style="color:red; margin-left:10px;">[Elimina]</a>
            </li>
        <?php endforeach; ?>
    </ul>

</body>

</html>