<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once("../includes/ligacao.php");
$active_loja = 'produtos';

// Processar adição ao carrinho
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_carrinho'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login/login.php");
        exit();
    }
    $produto_id = (int)$_POST['produto_id'];
    $user_id    = (int)$_SESSION['user_id'];
    // Verificar se já existe no carrinho
    $st = $conn->prepare("SELECT id, quantidade FROM carrinho WHERE user_id=? AND produto_id=? AND comprado=0");
    $st->bind_param("ii", $user_id, $produto_id);
    $st->execute();
    $ex = $st->get_result()->fetch_assoc();
    if ($ex) {
        $nova_qty = $ex['quantidade'] + 1;
        $conn->query("UPDATE carrinho SET quantidade=$nova_qty WHERE id={$ex['id']}");
    } else {
        $conn->query("INSERT INTO carrinho (user_id, produto_id, quantidade) VALUES ($user_id, $produto_id, 1)");
    }
    $msg = 'ok';
}

// Categorias
$res_cats = $conn->query("SELECT * FROM categorias ORDER BY nome");
$cats = [];
while ($c = $res_cats->fetch_assoc()) $cats[] = $c;

// Filtro de categoria
$cat_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;

if ($cat_id > 0) {
    $sql = "SELECT p.*, c.nome as cat_nome FROM produtos p LEFT JOIN categorias c ON p.categoria_id=c.id WHERE p.categoria_id=$cat_id ORDER BY p.nome";
} else {
    $sql = "SELECT p.*, c.nome as cat_nome FROM produtos p LEFT JOIN categorias c ON p.categoria_id=c.id ORDER BY p.nome";
}
$res_prods = $conn->query($sql);

// Nome da categoria ativa
$cat_nome_ativa = '';
if ($cat_id > 0) {
    foreach ($cats as $c) { if ($c['id'] == $cat_id) { $cat_nome_ativa = $c['nome']; break; } }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Driver Master</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../loja.css">
</head>
<body style="background:var(--bg);color:var(--text)">
<?php include "../includes/navbar_loja.php"; ?>

<div class="loja-main">
    <?php if ($msg === 'ok'): ?>
        <div class="alert alert-success" style="margin-bottom:16px">● Produto adicionado ao carrinho! <a href="carrinho.php" style="color:#4ade80">Ver carrinho</a></div>
    <?php endif; ?>

    <div class="page-header">
        <h1><?= $cat_nome_ativa ? htmlspecialchars($cat_nome_ativa) : 'Todos os Produtos' ?></h1>
    </div>

    <div class="loja-layout">
        <!-- Sidebar categorias -->
        <div class="loja-sidebar">
            <h3>Categorias</h3>
            <div class="categorias-list">
                <a href="produtos.php" class="cat-link <?= $cat_id === 0 ? 'active' : '' ?>">Todos os Produtos</a>
                <?php foreach ($cats as $c): ?>
                <a href="produtos.php?categoria=<?= $c['id'] ?>" class="cat-link <?= $cat_id === (int)$c['id'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($c['nome']) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Grid de produtos -->
        <div>
            <?php if ($res_prods->num_rows === 0): ?>
                <div class="alert alert-info">Nenhum produto encontrado nesta categoria.</div>
            <?php else: ?>
            <div class="produtos-grid">
            <?php while($p = $res_prods->fetch_assoc()):
                $stock = (int)$p['stock'];
                $stock_class = $stock <= 0 ? 'stock-out' : ($stock <= 3 ? 'stock-low' : 'stock-ok');
                $stock_txt   = $stock <= 0 ? 'Esgotado' : ($stock <= 3 ? "Só $stock restam" : "Em stock");
            ?>
                <div class="produto-card">
                    <?php if ($p['imagem'] && file_exists("../imagens/" . $p['imagem'])): ?>
                        <img src="../imagens/<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>">
                    <?php else: ?>
                        <div class="no-img">Sem imagem</div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h3><?= htmlspecialchars($p['nome']) ?></h3>
                        <?php if ($p['cat_nome']): ?>
                            <span class="badge badge-green" style="font-size:10px"><?= htmlspecialchars($p['cat_nome']) ?></span>
                        <?php endif; ?>
                        <p><?= htmlspecialchars(mb_strimwidth($p['descricao'] ?? '', 0, 70, '...')) ?></p>
                    </div>
                    <div class="card-footer">
                        <span class="preco"><?= number_format($p['preco'], 2, ',', '.') ?> €</span>
                        <span class="stock-badge <?= $stock_class ?>"><?= $stock_txt ?></span>
                    </div>
                    <div style="padding:0 14px 14px;display:flex;gap:8px">
                        <a href="detalhe.php?id=<?= $p['id'] ?>" class="btn btn-secondary btn-sm" style="flex:1;justify-content:center">Ver Detalhes</a>
                        <?php if ($stock > 0): ?>
                        <form method="post" style="flex:1">
                            <input type="hidden" name="produto_id" value="<?= $p['id'] ?>">
                            <button type="submit" name="add_carrinho" class="btn btn-primary btn-sm" style="width:100%;justify-content:center">
                                Adicionar
                            </button>
                        </form>
                        <?php else: ?>
                            <button class="btn btn-sm" style="flex:1;background:var(--surface2);color:var(--muted);cursor:not-allowed" disabled>Esgotado</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
