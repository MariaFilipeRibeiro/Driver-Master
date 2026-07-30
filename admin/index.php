<?php
require_once("../includes/auth_admin.php");
require_once("../includes/ligacao.php");
$active_admin = 'dashboard';
$depth_admin  = '../';

// Estatísticas
$total_prods    = $conn->query("SELECT COUNT(*) as n FROM produtos")->fetch_assoc()['n'];
$total_cats     = $conn->query("SELECT COUNT(*) as n FROM categorias")->fetch_assoc()['n'];
$total_users    = $conn->query("SELECT COUNT(*) as n FROM utilizadores")->fetch_assoc()['n'];
$total_encs     = $conn->query("SELECT COUNT(*) as n FROM carrinho WHERE comprado=1")->fetch_assoc()['n'];
$receita        = $conn->query("SELECT SUM(p.preco * c.quantidade) as total FROM carrinho c JOIN produtos p ON c.produto_id=p.id WHERE c.comprado=1")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir loja - Driver Master</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include "../includes/navbar_admin.php"; ?>

<div class="page-header">
    <h1>Dashboard</h1>
</div>

<!-- Cartões de estatísticas -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;margin-bottom:28px">
    <div class="card" style="margin:0;text-align:center">
        <div style="font-size:28px;font-weight:700;color:#4ade80"><?= $total_prods ?></div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px">Produtos</div>
    </div>
    <div class="card" style="margin:0;text-align:center">
        <div style="font-size:28px;font-weight:700;color:#60a5fa"><?= $total_cats ?></div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px">Categorias</div>
    </div>
    <div class="card" style="margin:0;text-align:center">
        <div style="font-size:28px;font-weight:700;color:#fb923c"><?= $total_users ?></div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px">Utilizadores</div>
    </div>
    <div class="card" style="margin:0;text-align:center">
        <div style="font-size:28px;font-weight:700;color:#f472b6"><?= $total_encs ?></div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px">Itens Vendidos</div>
    </div>
    <div class="card" style="margin:0;text-align:center">
        <div style="font-size:24px;font-weight:700;color:#4ade80"><?= number_format($receita, 2, ',', '.') ?> €</div>
        <div style="font-size:12px;color:var(--muted);margin-top:4px">Receita Total</div>
    </div>
</div>

</main></div></div>
</body>
</html>
