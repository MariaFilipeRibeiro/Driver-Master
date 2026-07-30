<?php
require_once("../includes/auth_admin.php");
require_once("../includes/ligacao.php");
$active_admin = 'categorias';
$depth_admin  = '../';

$erro    = '';
$sucesso = '';

// Inserir
if (isset($_POST['acao']) && $_POST['acao'] === 'inserir') {
    $nome = trim($_POST['nome'] ?? '');
    if ($nome === '') {
        $erro = 'O nome é obrigatório.';
    } else {
        $stmt = $conn->prepare("INSERT INTO categorias (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);
        if ($stmt->execute()) {
            $sucesso = "Categoria \"$nome\" criada!";
        } else {
            $erro = 'Erro: ' . $conn->error;
        }
    }
}

// Eliminar
if (isset($_GET['apagar'])) {
    $cid = (int)$_GET['apagar'];
    // Verificar se tem produtos
    $prods = $conn->query("SELECT COUNT(*) as n FROM produtos WHERE categoria_id=$cid")->fetch_assoc()['n'];
    if ($prods > 0) {
        $erro = "Não é possível eliminar esta categoria: tem $prods produto(s) associado(s).";
    } else {
        $conn->query("DELETE FROM categorias WHERE id=$cid");
        $sucesso = "Categoria eliminada.";
    }
}

// Editar (GET exibe form; POST guarda)
$editar_id  = intval($_GET['editar'] ?? 0);
$editar_row = null;
if ($editar_id) {
    $editar_row = $conn->query("SELECT * FROM categorias WHERE id=$editar_id")->fetch_assoc();
}

if (isset($_POST['acao']) && $_POST['acao'] === 'editar') {
    $cid   = intval($_POST['id'] ?? 0);
    $nome  = trim($_POST['nome'] ?? '');
    if ($nome === '') {
        $erro = 'O nome é obrigatório.';
    } else {
        $stmt = $conn->prepare("UPDATE categorias SET nome=? WHERE id=?");
        $stmt->bind_param("si", $nome, $cid);
        if ($stmt->execute()) {
            $sucesso = "Categoria atualizada!";
            $editar_id = 0; $editar_row = null;
        } else {
            $erro = 'Erro: ' . $conn->error;
        }
    }
}

$res = $conn->query("SELECT c.*, COUNT(p.id) as total_prods FROM categorias c LEFT JOIN produtos p ON p.categoria_id=c.id GROUP BY c.id ORDER BY c.nome");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Gerir</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include "../includes/navbar_admin.php"; ?>

<?php if ($erro):    ?><div class="alert alert-error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<?php if ($sucesso): ?><div class="alert alert-success">● <?= htmlspecialchars($sucesso) ?></div><?php endif; ?>

<div class="page-header"><h1>Categorias</h1></div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start">
    <!-- Formulário de adição / edição -->
    <div class="card" style="margin:0">
        <h3 style="font-size:14px;font-weight:700;margin-bottom:16px;color:var(--muted)">
            <?= $editar_row ? 'EDITAR CATEGORIA' : 'NOVA CATEGORIA' ?>
        </h3>
        <form action="categorias.php" method="post">
            <?php if ($editar_row): ?>
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id" value="<?= $editar_id ?>">
            <?php else: ?>
                <input type="hidden" name="acao" value="inserir">
            <?php endif; ?>
            <div class="form-group">
                <label>Nome da Categoria</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($editar_row['nome'] ?? '') ?>" required placeholder="Ex: Informática">
            </div>
            <div class="form-actions" style="margin-top:0;padding-top:14px">
                <button type="submit" class="btn <?= $editar_row ? 'btn-editar' : 'btn-success' ?>">
                    <?= $editar_row ? 'Guardar Alterações' : 'Adicionar Categoria' ?>
                </button>
                <?php if ($editar_row): ?>
                <a href="categorias.php" class="btn btn-secondary">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Lista de categorias -->
    <div class="table-wrapper" style="margin:0">
        <?php if ($res->num_rows === 0): ?>
            <div class="empty-state"><p>Nenhuma categoria.</p></div>
        <?php else: ?>
        <table>
            <thead><tr><th>ID</th><th>Nome</th><th>Produtos</th><th>Ações</th></tr></thead>
            <tbody>
            <?php while($c = $res->fetch_assoc()): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><strong><?= htmlspecialchars($c['nome']) ?></strong></td>
                <td><span class="badge badge-blue"><?= $c['total_prods'] ?></span></td>
                <td style="display:flex;gap:6px">
                    <a href="categorias.php?editar=<?= $c['id'] ?>" class="btn btn-editar btn-sm">Editar</a>
                    <a href="categorias.php?apagar=<?= $c['id'] ?>" class="btn btn-eliminar btn-sm" onclick="return confirm('Eliminar categoria?')">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

</main></div></div>
</body>
</html>
