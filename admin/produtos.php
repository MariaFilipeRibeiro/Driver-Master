<?php
// Verifica se o administrador está autenticado
require_once("../includes/auth_admin.php");

// Ligação à base de dados
require_once("../includes/ligacao.php");

// Define a página ativa na sidebar
$active_admin = 'produtos';
$depth_admin  = '../';

// Busca todos os produtos com o nome da categoria associada
// LEFT JOIN para incluir produtos sem categoria
$res = $conn->query("SELECT p.*, c.nome as cat_nome FROM produtos p LEFT JOIN categorias c ON p.categoria_id=c.id ORDER BY p.nome");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Gerir</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include "../includes/navbar_admin.php"; ?>

<!-- Mensagem de sucesso após criar, editar ou eliminar um produto -->
<?php if (isset($_GET['ok'])): ?>
    <div class="alert alert-success">● <?= htmlspecialchars($_GET['ok']) ?></div>
<?php endif; ?>

<div class="page-header">
    <h1>Produtos</h1>
    <!-- Botão para criar um novo produto -->
    <a href="produtos_novo.php" class="btn btn-success">+ Novo Produto</a>
</div>

<div class="table-wrapper">
<?php if ($res->num_rows === 0): ?>
    <!-- Mensagem quando não existem produtos -->
    <div class="empty-state"><p>Nenhum produto encontrado.</p></div>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Preço</th>
            <th>Stock</th>
            <th>Imagem</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php while($p = $res->fetch_assoc()): ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><strong><?= htmlspecialchars($p['nome']) ?></strong></td>
        <td>
            <!-- Mostra a categoria ou traço se não tiver categoria -->
            <?php if($p['cat_nome']): ?>
                <span class="badge badge-green"><?= htmlspecialchars($p['cat_nome']) ?></span>
            <?php else: ?>
                <span style="color:var(--muted)">—</span>
            <?php endif; ?>
        </td>
        <td><?= number_format($p['preco'], 2, ',', '.') ?> €</td>
        <td>
            <!-- Badge de stock: vermelho se esgotado, laranja se baixo, verde se disponível -->
            <?php if ($p['stock'] <= 0): ?>
                <span class="badge" style="background:rgba(239,68,68,.15);color:#f87171">Esgotado</span>
            <?php elseif ($p['stock'] <= 3): ?>
                <span class="badge" style="background:rgba(249,115,22,.15);color:#fb923c"><?= $p['stock'] ?></span>
            <?php else: ?>
                <span class="badge badge-green"><?= $p['stock'] ?></span>
            <?php endif; ?>
        </td>
        <td>
            <!-- Mostra a imagem se existir, caso contrário mostra "Sem img" -->
            <?php if ($p['imagem'] && file_exists("../imagens/" . $p['imagem'])): ?>
                <img src="../imagens/<?= htmlspecialchars($p['imagem']) ?>" width="45" height="45" style="object-fit:cover;border-radius:5px;border:1px solid var(--border)">
            <?php else: ?>
                <span style="color:var(--muted);font-size:12px">Sem img</span>
            <?php endif; ?>
        </td>
        <td style="display:flex;gap:6px">
            <!-- Botão para editar o produto -->
            <a href="produtos_editar.php?id=<?= $p['id'] ?>" class="btn btn-editar btn-sm">Editar</a>
            <!-- Botão para eliminar o produto com confirmação -->
            <a href="produtos_eliminar.php?id=<?= $p['id'] ?>&nome=<?= urlencode($p['nome']) ?>" class="btn btn-eliminar btn-sm" onclick="return confirm('Eliminar produto <?= htmlspecialchars(addslashes($p['nome'])) ?>?')">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php endif; ?>
</div>

</main></div></div>
</body>
</html>