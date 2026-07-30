<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once("../includes/ligacao.php");
$active_loja = 'inicio';

// Buscar categorias
$res_cats = $conn->query("SELECT * FROM categorias ORDER BY nome");

// Buscar produtos em destaque (últimos 8)
$res_dest = $conn->query("SELECT p.*, c.nome as cat_nome FROM produtos p LEFT JOIN categorias c ON p.categoria_id=c.id ORDER BY p.id DESC LIMIT 8");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <title>Driver Master</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../loja.css">
    <style>
        @font-face {
            font-family: 'Gyanko';
            src: url('../fonts/gyanko-regular.otf') format('opentype');
        }
    </style>
</head>
<body style="background:var(--bg);color:var(--text)">
<?php include "../includes/navbar_loja.php"; ?>
<div class="loja-main">
    <!-- Hero -->
    <div class="hero">
        <div>
            <h1>Bem-vindo à Driver Master</h1>
            <p>Porque cada peça mereçe o melhor.</p>
            <a href="produtos.php" class="btn btn-primary" style="margin-top:18px">Ver Todos os Produtos</a>
        </div>
        <img src="../Trabalho.png" alt="Driver Master" style="width:170px;opacity:0.85">
    </div>

    <?php if (!isset($_SESSION['user_id'])): ?>
    <div style="margin-bottom:24px;background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.25);color:#60a5fa;padding:11px 16px;border-radius:8px;font-size:13px;font-weight:500">
        ⓘ Para efetuar compras precisa de <a href="../login/login.php" style="color:#93c5fd">fazer login</a> ou <a href="../login/registo.php" style="color:#93c5fd">criar uma conta</a>.
    </div>
    <?php endif; ?>

    <!-- Categorias -->
    <div style="margin-bottom:32px">
        <h2 style="font-size:16px;font-weight:700;margin-bottom:14px">Categorias</h2>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <?php while($cat = $res_cats->fetch_assoc()): ?>
            <a href="produtos.php?categoria=<?= $cat['id'] ?>" class="cat-link" style="border:1px solid var(--border);background:var(--surface)">
                <img src="../imagensloja/icon_categorias.png" width="14" height="14" alt="">
                <?= htmlspecialchars($cat['nome']) ?>
            </a>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Produtos em destaque -->
    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <h2 style="font-size:16px;font-weight:700">Produtos em Destaque</h2>
            <a href="produtos.php" style="font-size:12px;color:var(--muted);text-decoration:none">Ver todos →</a>
        </div>

        <?php if ($res_dest->num_rows === 0): ?>
            <div class="alert alert-info">Ainda não existem produtos na loja.</div>
        <?php else: ?>
        <div class="produtos-grid">
        <?php while($p = $res_dest->fetch_assoc()): ?>
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
                    <p><?= htmlspecialchars(mb_strimwidth($p['descricao'] ?? '', 0, 60, '...')) ?></p>
                </div>
                <div class="card-footer">
                    <span class="preco"><?= number_format($p['preco'], 2, ',', '.') ?> €</span>
                    <a href="detalhe.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">Ver</a>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
