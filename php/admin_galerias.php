<?php
require 'conexao.php';

// Ações POST (deletar ou salvar edição)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $delete_id = (int)$_POST['delete_id'];
        $stmt = $pdo->prepare("DELETE FROM galerias WHERE id = ?");
        $stmt->execute([$delete_id]);
        header("Location: admin_galerias.php");
        exit;
    }

    if (isset($_POST['edit_id'], $_POST['titulo'], $_POST['caminho_imagem'])) {
        $edit_id = (int)$_POST['edit_id'];
        $titulo = $_POST['titulo'];
        $caminho_imagem = $_POST['caminho_imagem'];

        $stmt = $pdo->prepare("UPDATE galerias SET titulo = ?, caminho_imagem = ? WHERE id = ?");
        $stmt->execute([$titulo, $caminho_imagem, $edit_id]);

        header("Location: admin_galerias.php");
        exit;
    }
}

// Buscar galerias
$stmt = $pdo->query("SELECT * FROM galerias ORDER BY id DESC");
$galerias = $stmt->fetchAll(PDO::FETCH_ASSOC);

$edit_item = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM galerias WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_item = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Galerias</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 900px; margin: auto;}
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px;}
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: top;}
        th { background: #eee; }
        form.inline { display: inline; }
        input[type=text] { width: 100%; padding: 6px; }
        .btn { padding: 6px 12px; cursor: pointer; border: none; border-radius: 4px; }
        .btn-edit { background-color: #4CAF50; color: white; }
        .btn-delete { background-color: #f44336; color: white; }
        .btn-cancel { background-color: #999; color: white; margin-left: 10px; }
        h1, h2 { text-align: center; }
        img.preview { max-width: 150px; margin-top: 8px; border: 1px solid #ccc; }
    </style>
</head>
<body>

    <h1>Admin - Galerias</h1>

    <?php if ($edit_item): ?>
        <h2>Editar Galeria ID #<?= htmlspecialchars($edit_item['id']) ?></h2>
        <form method="POST" action="admin_galerias.php">
            <input type="hidden" name="edit_id" value="<?= htmlspecialchars($edit_item['id']) ?>">
            <label>Título:<br>
                <input type="text" name="titulo" required value="<?= htmlspecialchars($edit_item['titulo']) ?>">
            </label><br><br>
            <label>Caminho da Imagem:<br>
                <input type="text" name="caminho_imagem" required value="<?= htmlspecialchars($edit_item['caminho_imagem']) ?>">
            </label><br>
            <?php if (!empty($edit_item['caminho_imagem'])): ?>
                <img src="<?= htmlspecialchars($edit_item['caminho_imagem']) ?>" alt="Preview" class="preview">
            <?php endif; ?>
            <br><br>
            <button type="submit" class="btn btn-edit">Salvar Alterações</button>
            <a href="admin_galerias.php" class="btn btn-cancel">Cancelar</a>
        </form>
        <hr>
    <?php endif; ?>

    <?php if (count($galerias) === 0): ?>
        <p>Nenhuma imagem cadastrada.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Imagem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($galerias as $gal): ?>
                    <tr>
                        <td><?= htmlspecialchars($gal['id']) ?></td>
                        <td><?= htmlspecialchars($gal['titulo']) ?></td>
                        <td><img src="<?= htmlspecialchars($gal['caminho_imagem']) ?>" alt="<?= htmlspecialchars($gal['titulo']) ?>" style="max-width:100px;"></td>
                        <td>
                            <a href="admin_galerias.php?edit_id=<?= $gal['id'] ?>" class="btn btn-edit">Editar</a>
                            <form method="POST" action="admin_galerias.php" class="inline" onsubmit="return confirm('Tem certeza que deseja deletar esta imagem?');">
                                <input type="hidden" name="delete_id" value="<?= $gal['id'] ?>">
                                <button type="submit" class="btn btn-delete">Deletar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>

<h2>Adicionar Nova Imagem</h2>
<form method="POST" action="admin_galerias.php">
    <input type="hidden" name="add_nova" value="1">
    <label>Título:<br>
        <input type="text" name="titulo" required>
    </label><br><br>
    <label>Caminho da Imagem:<br>
        <input type="text" name="caminho_imagem" required placeholder="ex: img/nome_da_imagem.jpg">
    </label><br><br>
    <button type="submit" class="btn btn-edit">Adicionar</button>
</form>
<hr>

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_nova'])) {
        $titulo = $_POST['titulo'];
        $caminho_imagem = $_POST['caminho_imagem'];

        $stmt = $pdo->prepare("INSERT INTO galerias (titulo, caminho_imagem) VALUES (?, ?)");
        $stmt->execute([$titulo, $caminho_imagem]);

        header("Location: admin_galerias.php");
        exit;
    }

    // ... código existente para delete e edit
}
