<?php
require_once("../includes/auth_admin.php");
require_once("../includes/ligacao.php");
$active_admin = 'produtos';
$depth_admin  = '../';

$id = intval($_GET['id'] ?? $_POST['id'] ?? 0);
if (!$id) { header("Location: produtos.php"); exit(); }

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome         = trim($_POST['nome'] ?? '');
    $descricao    = trim($_POST['descricao'] ?? '');
    $preco        = floatval($_POST['preco'] ?? 0);
    $stock        = intval($_POST['stock'] ?? 0);
    $categoria_id = intval($_POST['categoria_id'] ?? 0);
    $imagem_atual = $_POST['imagem_atual'] ?? null;

    if ($nome === '') { $erro = 'O nome é obrigatório.'; }
    else {
        $imagem = $imagem_atual;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $ext     = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];
            if (in_array($ext, $allowed)) {
                $imagem = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['imagem']['tmp_name'], '../imagens/' . $imagem);
            } else {
                $erro = 'Formato de imagem inválido.';
            }
        }

        if (!$erro) {
            $stmt = $conn->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, stock=?, categoria_id=?, imagem=? WHERE id=?");
            $stmt->bind_param("ssdiisi", $nome, $descricao, $preco, $stock, $categoria_id, $imagem, $id);
            if ($stmt->execute()) {
                header("Location: produtos.php?ok=" . urlencode("Produto \"$nome\" atualizado com sucesso!"));
                exit();
            } else {
                $erro = 'Erro ao guardar: ' . $conn->error;
            }
        }
    }
}

// Buscar produto
$stmt = $conn->prepare("SELECT * FROM produtos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
if (!$p) { header("Location: produtos.php"); exit(); }

$res_cats = $conn->query("SELECT * FROM categorias ORDER BY nome");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produtos - Gerir</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include "../includes/navbar_admin.php"; ?>

<div class="page-header">
    <h1>Editar Produto</h1>
    <a href="produtos.php" class="btn btn-secondary">← Voltar</a>
</div>

<?php if ($erro): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="card">
    <form action="produtos_editar.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="imagem_atual" value="<?= htmlspecialchars($p['imagem'] ?? '') ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Nome do Produto *</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? $p['nome']) ?>" required>
            </div>
            <div class="form-group">
                <label>Categoria</label>
                <select name="categoria_id">
                    <option value="0">Sem categoria</option>
                    <?php while($c = $res_cats->fetch_assoc()):
                        $sel = (($p['categoria_id'] == $c['id'])) ? 'selected' : '';
                    ?>
                    <option value="<?= $c['id'] ?>" <?= $sel ?>><?= htmlspecialchars($c['nome']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao" rows="3"><?= htmlspecialchars($_POST['descricao'] ?? $p['descricao']) ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Preço (€) *</label>
                <input type="number" name="preco" step="0.01" min="0" value="<?= htmlspecialchars($_POST['preco'] ?? $p['preco']) ?>" required>
            </div>
            <div class="form-group">
                <label>Stock *</label>
                <input type="number" name="stock" min="0" value="<?= htmlspecialchars($_POST['stock'] ?? $p['stock']) ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label>Imagem do Produto</label>
            <?php if ($p['imagem'] && file_exists("../imagens/" . $p['imagem'])): ?>
                <div style="margin-bottom:8px">
                    <img src="../imagens/<?= htmlspecialchars($p['imagem']) ?>" width="80" style="border-radius:6px;border:1px solid var(--border)">
                    <span style="font-size:12px;color:var(--muted);margin-left:8px">Imagem atual</span>
                </div>
            <?php endif; ?>
            <input type="file" name="imagem" accept="image/*">
            <small style="color:var(--muted)">Deixe em branco para manter a imagem atual</small>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-editar">Guardar Alterações</button>
            <a href="produtos.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

</main></div></div>
</body>
</html>
