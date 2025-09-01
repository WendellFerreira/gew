<?php
require 'conexao.php';

// Ações POST (deletar ou salvar edição)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $delete_id = (int)$_POST['delete_id'];
        $stmt = $pdo->prepare("DELETE FROM especialidades WHERE id = ?");
        $stmt->execute([$delete_id]);
        header("Location: admin_especialidades.php");
        exit;
    }

    if (isset($_POST['edit_id'], $_POST['titulo'], $_POST['descricao'], $_POST['icone'])) {
        $edit_id = (int)$_POST['edit_id'];
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $icone = $_POST['icone'];

        $stmt = $pdo->prepare("UPDATE especialidades SET titulo = ?, descricao = ?, icone = ? WHERE id = ?");
        $stmt->execute([$titulo, $descricao, $icone, $edit_id]);

        header("Location: admin_especialidades.php");
        exit;
    }
}

// Buscar especialidades
$stmt = $pdo->query("SELECT * FROM especialidades ORDER BY id DESC");
$especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

$edit_item = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM especialidades WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_item = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Especialidades</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 900px; margin: auto;}
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px;}
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: top;}
        th { background: #eee; }
        form.inline { display: inline; }
        textarea, input[type=text] { width: 100%; padding: 6px; }
        .btn { padding: 6px 12px; cursor: pointer; border: none; border-radius: 4px; }
        .btn-edit { background-color: #4CAF50; color: white; }
        .btn-delete { background-color: #f44336; color: white; }
        .btn-cancel { background-color: #999; color: white; margin-left: 10px; }
        h1, h2 { text-align: center; }
    </style>
</head>
<body>

    <h1>Admin - Especialidades</h1>

    <?php if ($edit_item): ?>
        <h2>Editar Especialidade ID #<?= htmlspecialchars($edit_item['id']) ?></h2>
        <form method="POST" action="admin_especialidades.php">
            <input type="hidden" name="edit_id" value="<?= htmlspecialchars($edit_item['id']) ?>">
            <label>Título:<br>
                <input type="text" name="titulo" required value="<?= htmlspecialchars($edit_item['titulo']) ?>">
            </label><br><br>
            <label>Descrição:<br>
                <textarea name="descricao" required><?= htmlspecialchars($edit_item['descricao']) ?></textarea>
            </label><br><br>
            <label>Ícone (classe bootstrap icons):<br>
                <input type="text" name="icone" required value="<?= htmlspecialchars($edit_item['icone']) ?>">
            </label><br><br>
            <button type="submit" class="btn btn-edit">Salvar Alterações</button>
            <a href="admin_especialidades.php" class="btn btn-cancel">Cancelar</a>
        </form>
        <hr>
    <?php endif; ?>

    <?php if (count($especialidades) === 0): ?>
        <p>Nenhuma especialidade cadastrada.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Ícone</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($especialidades as $esp): ?>
                    <tr>
                        <td><?= htmlspecialchars($esp['id']) ?></td>
                        <td><?= htmlspecialchars($esp['titulo']) ?></td>
                        <td><?= nl2br(htmlspecialchars($esp['descricao'])) ?></td>
                        <td><i class="bi <?= htmlspecialchars($esp['icone']) ?>"></i> <?= htmlspecialchars($esp['icone']) ?></td>
                        <td>
                            <a href="admin_especialidades.php?edit_id=<?= $esp['id'] ?>" class="btn btn-edit">Editar</a>
                            <form method="POST" action="admin_especialidades.php" class="inline" onsubmit="return confirm('Tem certeza que deseja deletar esta especialidade?');">
                                <input type="hidden" name="delete_id" value="<?= $esp['id'] ?>">
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

<h2>Adicionar Nova Especialidade</h2>
<form method="POST" action="admin_especialidades.php">
    <input type="hidden" name="add_nova" value="1">
    <label>Título:<br>
        <input type="text" name="titulo" required>
    </label><br><br>
    <label>Descrição:<br>
        <textarea name="descricao" required></textarea>
    </label><br><br>
    <label>Ícone (classe bootstrap icons):<br>
        <input type="text" name="icone" required>
    </label><br><br>
    <button type="submit" class="btn btn-edit">Adicionar</button>
</form>
<hr>

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_nova'])) {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $icone = $_POST['icone'];

        $stmt = $pdo->prepare("INSERT INTO especialidades (titulo, descricao, icone) VALUES (?, ?, ?)");
        $stmt->execute([$titulo, $descricao, $icone]);

        header("Location: admin_especialidades.php");
        exit;
    }

    // ... código existente para delete e edit
}
