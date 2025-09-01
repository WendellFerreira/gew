<?php
require 'conexao.php';

// Busca todos os contatos
$stmt = $pdo->query("SELECT * FROM contatos ORDER BY data_envio DESC");
$contatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Contatos Recebidos</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h1>Contatos Recebidos</h1>
    <?php if (count($contatos) === 0): ?>
        <p>Nenhum contato recebido ainda.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Celular</th>
                    <th>Mensagem</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contatos as $contato): ?>
                    <tr>
                        <td><?= htmlspecialchars($contato['id']) ?></td>
                        <td><?= htmlspecialchars($contato['nome']) ?></td>
                        <td><?= htmlspecialchars($contato['email']) ?></td>
                        <td><?= htmlspecialchars($contato['celular']) ?></td>
                        <td><?= nl2br(htmlspecialchars($contato['mensagem'])) ?></td>
                        <td><?= htmlspecialchars($contato['data_envio']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>

<?php
require 'conexao.php';

// Ações: deletar ou salvar edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        // Deletar contato
        $delete_id = (int)$_POST['delete_id'];
        $stmt = $pdo->prepare("DELETE FROM contatos WHERE id = ?");
        $stmt->execute([$delete_id]);
        header("Location: admin_contatos.php");
        exit;
    }

    if (isset($_POST['edit_id'], $_POST['nome'], $_POST['email'], $_POST['celular'], $_POST['mensagem'])) {
        // Atualizar contato
        $edit_id = (int)$_POST['edit_id'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $celular = $_POST['celular'];
        $mensagem = $_POST['mensagem'];

        $stmt = $pdo->prepare("UPDATE contatos SET nome = ?, email = ?, celular = ?, mensagem = ? WHERE id = ?");
        $stmt->execute([$nome, $email, $celular, $mensagem, $edit_id]);

        header("Location: admin_contatos.php");
        exit;
    }
}

// Buscar contatos
$stmt = $pdo->query("SELECT * FROM contatos ORDER BY data_envio DESC");
$contatos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Se veio um parâmetro de edição
$edit_item = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM contatos WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_item = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Contatos</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 900px; margin: auto;}
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px;}
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: top;}
        th { background: #eee; }
        form.inline { display: inline; }
        textarea { width: 100%; height: 80px; }
        input[type=text], input[type=email], input[type=tel] { width: 100%; padding: 6px; }
        .btn { padding: 6px 12px; cursor: pointer; border: none; border-radius: 4px; }
        .btn-edit { background-color: #4CAF50; color: white; }
        .btn-delete { background-color: #f44336; color: white; }
        .btn-cancel { background-color: #999; color: white; margin-left: 10px; }
        h1, h2 { text-align: center; }
    </style>
</head>
<body>

    <h1>Admin - Contatos Recebidos</h1>

    <?php if ($edit_item): ?>
        <h2>Editar Contato ID #<?= htmlspecialchars($edit_item['id']) ?></h2>
        <form method="POST" action="admin_contatos.php">
            <input type="hidden" name="edit_id" value="<?= htmlspecialchars($edit_item['id']) ?>">
            <label>Nome:<br>
                <input type="text" name="nome" required value="<?= htmlspecialchars($edit_item['nome']) ?>">
            </label><br><br>
            <label>E-mail:<br>
                <input type="email" name="email" required value="<?= htmlspecialchars($edit_item['email']) ?>">
            </label><br><br>
            <label>Celular:<br>
                <input type="tel" name="celular" value="<?= htmlspecialchars($edit_item['celular']) ?>">
            </label><br><br>
            <label>Mensagem:<br>
                <textarea name="mensagem" required><?= htmlspecialchars($edit_item['mensagem']) ?></textarea>
            </label><br><br>
            <button type="submit" class="btn btn-edit">Salvar Alterações</button>
            <a href="admin_contatos.php" class="btn btn-cancel">Cancelar</a>
        </form>

        <hr>
    <?php endif; ?>

    <?php if (count($contatos) === 0): ?>
        <p>Nenhum contato recebido ainda.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Celular</th>
                    <th>Mensagem</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contatos as $contato): ?>
                    <tr>
                        <td><?= htmlspecialchars($contato['id']) ?></td>
                        <td><?= htmlspecialchars($contato['nome']) ?></td>
                        <td><?= htmlspecialchars($contato['email']) ?></td>
                        <td><?= htmlspecialchars($contato['celular']) ?></td>
                        <td><?= nl2br(htmlspecialchars($contato['mensagem'])) ?></td>
                        <td><?= htmlspecialchars($contato['data_envio']) ?></td>
                        <td>
                            <a href="admin_contatos.php?edit_id=<?= $contato['id'] ?>" class="btn btn-edit">Editar</a>

                            <form method="POST" action="admin_contatos.php" class="inline" onsubmit="return confirm('Tem certeza que deseja deletar este contato?');">
                                <input type="hidden" name="delete_id" value="<?= $contato['id'] ?>">
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
