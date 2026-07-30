<?php
// Verifica se o administrador está autenticado
require_once("../includes/auth_admin.php");

// Ligação à base de dados
require_once("../includes/ligacao.php");

// Define a página ativa na sidebar
$active_admin = 'produtos';
$depth_admin  = '../';

$erro = '';

// Quando o formulário é submetido, recolhe e valida os dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recolhe e limpa os dados do formulário
    $nome         = trim($_POST['nome'] ?? '');
    $descricao    = trim($_POST['descricao'] ?? '');
    $preco        = floatval($_POST['preco'] ?? 0);
    $stock        = intval($_POST['stock'] ?? 0);
    $categoria_id = intval($_POST['categoria_id'] ?? 0);
    $imagem       = null;

    // Validação do nome é obrigatório
    if ($nome === '') {
        $erro = 'O nome é obrigatório.';
    } else {

        // ── PROCESSAR IMAGEM ─────────────────────────────────
        // Verifica se foi enviado um ficheiro de imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            // Obtém a extensão do ficheiro em minúsculas
            $ext     = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($ext, $allowed)) {
                // Gera um nome único para evitar conflitos com ficheiros
                $imagem = uniqid() . '.' . $ext;
                // Move o ficheiro para a pasta de imagens
                move_uploaded_file($_FILES['imagem']['tmp_name'], '../imagens/' . $imagem);
            } else {
                $erro = 'Formato de imagem inválido.';
            }
        }

        // Só insere se não houver erros de validação
        if (!$erro) {
            // Usa prepared statement para evitar SQL injection
            $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, stock, categoria_id, imagem) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdiis", $nome, $descricao, $preco, $stock, $categoria_id, $imagem);

            if ($stmt->execute()) {
                // Redireciona para a lista com mensagem de sucesso
                header("Location: produtos.php?ok=" . urlencode("Produto \"$nome\" criado com sucesso!"));
                exit();
            } else {
                $erro = 'Erro ao guardar: ' . $conn->error;
            }
        }
    }
}

// Busca todas as categorias para preencher o dropdown
$res_cats = $conn->query("SELECT * FROM categorias ORDER BY nome");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Produto - Gerir</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include "../includes/navbar_admin.php"; ?>

<div class="page-header">
    <h1>Novo Produto</h1>
    <!-- Botão para voltar à lista de produtos -->
    <a href="produtos.php" class="btn btn-secondary">← Voltar</a>
</div>

<!-- Mensagem de erro se a validação falhar -->
<?php if ($erro): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="card">
    <!-- enctype necessário para envio de ficheiros (imagem) -->
    <form action="produtos_novo.php" method="post" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label>Nome do Produto *</label>
                <!-- Mantém o valor preenchido em caso de erro -->
                <input type="text" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Categoria</label>
                <!-- Dropdown com todas as categorias disponíveis -->
                <select name="categoria_id">
                    <option value="0">Sem categoria</option>
                    <?php while($c = $res_cats->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>" <?= (($_POST['categoria_id'] ?? 0) == $c['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nome']) ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao" rows="3"><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Preço (€) *</label>
                <!-- step="0.01" permite valores decimais como 9.99 -->
                <input type="number" name="preco" step="0.01" min="0" value="<?= htmlspecialchars($_POST['preco'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Stock *</label>
                <input type="number" name="stock" min="0" value="<?= htmlspecialchars($_POST['stock'] ?? '0') ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label>Imagem do Produto</label>
            <!-- accept limita a seleção a ficheiros de imagem -->
            <input type="file" name="imagem" accept="image/*">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Guardar Produto</button>
            <a href="produtos.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

</main></div></div>
</body>
</html>