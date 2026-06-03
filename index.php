<?php
require_once 'connessione.php';

$lang = 'it';

if (!empty($_GET['lang'])) {
    $lang = $_GET['lang'];
    setcookie('lang', $lang, time() + (86400 * 30), "/");
} elseif (!empty($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
}

if (!in_array($lang, ['zh', 'it', 'en'])) {
    $lang = 'it';
}

$lang_pack = [
    'zh' => [
        'title' => '开放图书馆',
        'sub_list' => '可用图书列表',
        'gest_books' => '图书管理',
        'gest_cats' => '分类管理',
        'action' => '操作',
        'delete' => '删除',
        'presta' => '借阅',
        'restituisci' => '归还',
        'disponibile' => '可借阅',
        'in_prestito' => '已借出',
        'user' => '用户名',
        'code' => '取书码',
        'add_book' => '添加图书',
        'add_cat' => '添加分类',
        'new_cat' => '新分类名称',
        'select_cat' => '选择分类',
        'confirm' => '你确定吗？'
    ],
    'it' => [
        'title' => 'Biblioteca Aperta',
        'sub_list' => 'Libri Disponibili',
        'gest_books' => 'Gestione Libri',
        'gest_cats' => 'Gestione Categorie',
        'action' => 'Azioni',
        'delete' => 'Elimina',
        'presta' => 'Presta',
        'restituisci' => 'Restituisci',
        'disponibile' => 'Disponibile',
        'in_prestito' => 'In prestito',
        'user' => 'Utente',
        'code' => 'Codice',
        'add_book' => 'Aggiungi Libro',
        'add_cat' => 'Aggiungi',
        'new_cat' => 'Nuova categoria',
        'select_cat' => 'Seleziona Categoria',
        'confirm' => 'Sei sicuro?'
    ],
    'en' => [
        'title' => 'Open Library',
        'sub_list' => 'Available Books',
        'gest_books' => 'Book Management',
        'gest_cats' => 'Category Management',
        'action' => 'Actions',
        'delete' => 'Delete',
        'presta' => 'Borrow',
        'restituisci' => 'Return',
        'disponibile' => 'Available',
        'in_prestito' => 'Borrowed',
        'user' => 'Username',
        'code' => 'Code',
        'add_book' => 'Add Book',
        'add_cat' => 'Add',
        'new_cat' => 'New Category',
        'select_cat' => 'Select Category',
        'confirm' => 'Are you sure?'
    ]
];

$txt = $lang_pack[$lang];

$stmt_cat = $pdo->query("SELECT * FROM categoria");
$categorie = $stmt_cat->fetchAll();

$sql = "SELECT libro.id_libro, libro.isbn, libro.titolo, libro.autore, libro.stato, categoria.nome_categoria 
        FROM libro
        LEFT JOIN categoria ON libro.id_categoria = categoria.id_categoria";
$stmt = $pdo->query($sql);
$libri = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <title><?= $txt['title'] ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div style="text-align: right; margin-bottom: 20px;">
        <a href="index.php?lang=zh">🇨🇳 中文</a> |
        <a href="index.php?lang=it">🇮🇹 Italiano</a> |
        <a href="index.php?lang=en">🇬🇧 English</a>
    </div>

    <h1><?= $txt['title'] ?></h1>
    <hr>

    <h2><?= $txt['sub_list'] ?></h2>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ISBN</th>
            <th><?= $lang === 'zh' ? '书名' : 'Titolo' ?></th>
            <th><?= $lang === 'zh' ? '作者' : 'Autore' ?></th>
            <th><?= $lang === 'zh' ? '分类' : 'Categoria' ?></th>
            <th><?= $lang === 'zh' ? '状态' : 'Stato' ?></th>
            <th><?= $txt['action'] ?></th>
        </tr>
        <?php foreach ($libri as $libro): ?>
            <tr>
                <td><?= htmlspecialchars($libro['isbn']) ?></td>
                <td><?= htmlspecialchars($libro['titolo']) ?></td>
                <td><?= htmlspecialchars($libro['autore']) ?></td>
                <td><?= htmlspecialchars($libro['nome_categoria'] ?? '') ?></td>
                <td><?= $libro['stato'] ? $txt['disponibile'] : $txt['in_prestito'] ?></td>
                <td>
                    <a href="elimina_libro.php?id=<?= $libro['id_libro'] ?>"
                        onclick="return confirm('<?= $txt['confirm'] ?>')" style="color:red;">[<?= $txt['delete'] ?>]</a>

                    <span style="margin: 0 10px;">|</span>

                    <?php if ($libro['stato']): ?>
                        <form action="presta_libro.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id_libro" value="<?= $libro['id_libro'] ?>">
                            <input type="text" name="nome_utente" placeholder="<?= $txt['user'] ?>" required size="10">
                            <input type="text" name="codice_ritiro" placeholder="<?= $txt['code'] ?>" required size="6">
                            <button type="submit">[<?= $txt['presta'] ?>]</button>
                        </form>
                    <?php else: ?>
                        <form action="restituisci_libro.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id_libro" value="<?= $libro['id_libro'] ?>">
                            <input type="text" name="codice_ritiro" placeholder="<?= $txt['code'] ?>" required size="12">
                            <button type="submit">[<?= $txt['restituisci'] ?>]</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <hr>

    <h2><?= $txt['gest_books'] ?></h2>
    <form action="aggiungi_libro.php" method="POST" style="margin-bottom: 15px;">
        <input type="text" name="isbn" placeholder="ISBN" required>
        <input type="text" name="titolo" placeholder="<?= $lang === 'zh' ? '书名' : 'Titolo' ?>" required>
        <input type="text" name="autore" placeholder="<?= $lang === 'zh' ? '作者' : 'Autore' ?>" required>
        <select name="id_categoria" required>
            <option value=""><?= $txt['select_cat'] ?></option>
            <?php foreach ($categorie as $cat): ?>
                <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nome_categoria']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit"><?= $txt['add_book'] ?></button>
    </form>

    <hr>

    <h2><?= $txt['gest_cats'] ?></h2>

    <form action="aggiungi_categoria.php" method="POST" style="margin-bottom: 15px;">
        <input type="text" name="nome_categoria" placeholder="<?= $txt['new_cat'] ?>" required>
        <button type="submit"><?= $txt['add_cat'] ?></button>
    </form>

    <ul>
        <?php foreach ($categorie as $cat): ?>
            <li>
                <?= htmlspecialchars($cat['nome_categoria']) ?>
                <a href="elimina_categoria.php?id=<?= $cat['id_categoria'] ?>"
                    onclick="return confirm('<?= $txt['confirm'] ?>')"
                    style="color:red; margin-left:10px;">[<?= $txt['delete'] ?>]</a>
            </li>
        <?php endforeach; ?>
    </ul>

</body>

</html>
