<?php
// Conexão com o banco de dados (MySQL)
// Ajuste os dados conforme seu ambiente
$host = "localhost";
$dbname = "gwsweetlab";
$user = "seu_usuario";
$pass = "sua_senha";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recebe os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $celular = $_POST['celular'] ?? '';
    $mensagem = $_POST['mensagem'] ?? '';

    // Validação simples
    if (!$nome || !$email || !$mensagem) {
        throw new Exception("Por favor, preencha todos os campos obrigatórios.");
    }

    // Preparar e executar a query
    $sql = "INSERT INTO contatos (nome, email, celular, mensagem) VALUES (:nome, :email, :celular, :mensagem)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':celular' => $celular,
        ':mensagem' => $mensagem
    ]);

    // Redireciona ou exibe mensagem de sucesso
    echo "Mensagem enviada com sucesso! Obrigado pelo contato.";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<?php
// Conexão com banco (ajuste conforme seu ambiente)
$host = "localhost";
$dbname = "gwsweetlab";
$user = "seu_usuario";
$pass = "sua_senha";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Busca especialidades
    $stmt = $pdo->query("SELECT * FROM especialidades ORDER BY titulo");
    $especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro ao conectar: " . $e->getMessage();
    $especialidades = [];
}
?>

<section class="especialidades" id="especialidade">
    <div class="interface">
        <h2 class="titulo">NOSSAS <span>ESPECIALIDADES</span></h2>
        <div class="flex">
            <?php foreach ($especialidades as $esp): ?>
                <a class="tecnica" href="#projetos">
                    <div class="especialidades-box">
                        <i class="bi <?= htmlspecialchars($esp['icone']) ?>"></i>
                        <h3><?= htmlspecialchars($esp['titulo']) ?></h3>
                        <p><?= htmlspecialchars($esp['descricao']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
try {
    // Conexão já criada antes (ou faça novamente)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Busca galeria
    $stmt = $pdo->query("SELECT * FROM galerias ORDER BY id DESC");
    $galerias = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro ao conectar: " . $e->getMessage();
    $galerias = [];
}
?>

<section class="portfolio" id="projetos">
    <div class="interface">
        <h2 class="titulo">NOSSA <span>GALERIA</span></h2>
        <div class="flex">
            <?php foreach ($galerias as $gal): ?>
                <div class="img-port">
                    <img src="<?= htmlspecialchars($gal['caminho_imagem']) ?>" alt="<?= htmlspecialchars($gal['titulo']) ?>">
                    <div class="overlay"><?= htmlspecialchars($gal['titulo']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
