<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}
require_once("../includes/ligacao.php");
$active_loja = 'encomendas';
$user_id = (int)$_SESSION['user_id'];

// Buscar encomendas agrupadas por data (cada "compra" é um grupo de itens comprados de uma vez)
$res = $conn->query("
    SELECT c.id as cid, c.data, c.quantidade, p.nome, p.preco, p.imagem
    FROM carrinho c
    JOIN produtos p ON c.produto_id=p.id
    WHERE c.user_id=$user_id AND c.comprado=1
    ORDER BY c.data DESC, c.id DESC
");

// Agrupar por data (aproximada, segundo)
$encomendas = [];
while ($r = $res->fetch_assoc()) {
    $chave = substr($r['data'], 0, 16); // "YYYY-MM-DD HH:MM"
    $encomendas[$chave][] = $r;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encomendas - Driver Master</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../loja.css">
</head>
<body style="background:var(--bg);color:var(--text)">
<?php include "../includes/navbar_loja.php"; ?>

<div class="loja-main">
    <div class="page-header">
        <h1>As Minhas Encomendas</h1>
        <a href="produtos.php" class="btn btn-secondary">← Continuar a Comprar</a>
    </div>

    <?php if (empty($encomendas)): ?>
        <div class="alert alert-info">Ainda não realizou nenhuma encomenda. <a href="produtos.php" style="color:#93c5fd">Ir às compras</a></div>
    <?php else: ?>
        <?php $num = count($encomendas); $i = $num; ?>
        <?php foreach ($encomendas as $data => $itens): ?>
        <div class="encomenda-card">
            <div class="encomenda-header">
                <div>
                    <strong>Encomenda #<?= $i-- ?></strong>
                    <span style="color:var(--muted);margin-left:12px;font-size:12px"><?= date('d/m/Y H:i', strtotime($data . ':00')) ?></span>
                </div>
                <?php
                $total_enc = array_sum(array_map(fn($r) => $r['preco'] * $r['quantidade'], $itens));
                ?>
                <span style="color:#4ade80;font-weight:700"><?= number_format($total_enc, 2, ',', '.') ?> €</span>
            </div>
            <div class="encomenda-body">
                <table style="width:100%">
                    <thead>
                        <tr>
                            <th style="padding:8px 0;border-bottom:1px solid var(--border);font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.6px">Produto</th>
                            <th style="padding:8px 0;border-bottom:1px solid var(--border);font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.6px">Qtd.</th>
                            <th style="padding:8px 0;border-bottom:1px solid var(--border);font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.6px">Preço Unit.</th>
                            <th style="padding:8px 0;border-bottom:1px solid var(--border);font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.6px">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($itens as $item): ?>
                    <tr>
                        <td style="padding:10px 0;border-bottom:1px solid var(--border);font-size:13px">
                            <?= htmlspecialchars($item['nome']) ?>
                        </td>
                        <td style="padding:10px 0;border-bottom:1px solid var(--border);font-size:13px;color:var(--muted)"><?= $item['quantidade'] ?></td>
                        <td style="padding:10px 0;border-bottom:1px solid var(--border);font-size:13px;color:var(--muted)"><?= number_format($item['preco'], 2, ',', '.') ?> €</td>
                        <td style="padding:10px 0;border-bottom:1px solid var(--border);font-size:13px;font-weight:600"><?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?> €</td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
