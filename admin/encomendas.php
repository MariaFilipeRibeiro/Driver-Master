<?php
require_once("../includes/auth_admin.php");
require_once("../includes/ligacao.php");
$active_admin = 'encomendas';
$depth_admin  = '../';

// Pesquisa todas as encomendas com dados do utilizador
$res = $conn->query("
    SELECT c.id as cid, c.data, c.quantidade, c.comprado,
           p.nome as produto, p.preco,
           u.nome as cliente, u.email
    FROM carrinho c
    JOIN produtos p ON c.produto_id=p.id
    JOIN utilizadores u ON c.user_id=u.id
    WHERE c.comprado=1
    ORDER BY c.data DESC, c.id DESC
");

// Agrupar por (user_id, data minuto)
$todas = [];
while ($r = $res->fetch_assoc()) $todas[] = $r;

// Agrupar em "encomendas" (mesmo cliente, mesma data até ao minuto)
$grupos = [];
foreach ($todas as $r) {
    $chave = $r['email'] . '|' . substr($r['data'], 0, 16);
    $grupos[$chave]['info'] = ['cliente' => $r['cliente'], 'email' => $r['email'], 'data' => $r['data']];
    $grupos[$chave]['itens'][] = $r;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encomendas - Gerir</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../loja.css">
</head>
<body>
<?php include "../includes/navbar_admin.php"; ?>

<div class="page-header">
    <h1>Encomendas</h1>
    <span style="color:var(--muted);font-size:13px"><?= count($grupos) ?> encomenda(s)</span>
</div>

<?php if (empty($grupos)): ?>
    <div class="alert alert-info">Ainda não existem encomendas.</div>
<?php else: ?>
    <?php $num = count($grupos); $i = $num; ?>
    <?php foreach ($grupos as $chave => $grupo): ?>
    <?php
        $info = $grupo['info'];
        $itens = $grupo['itens'];
        $total = array_sum(array_map(fn($r) => $r['preco'] * $r['quantidade'], $itens));
    ?>
    <div class="encomenda-card" style="margin-bottom:16px">
        <div class="encomenda-header">
            <div>
                <strong>Encomenda #<?= $i-- ?></strong>
                <span style="color:var(--muted);margin-left:10px;font-size:12px"><?= date('d/m/Y H:i', strtotime($info['data'])) ?></span>
            </div>
            <div style="display:flex;align-items:center;gap:16px">
                <span style="font-size:12px;color:var(--muted)">
                    👤 <?= htmlspecialchars($info['cliente']) ?> &lt;<?= htmlspecialchars($info['email']) ?>&gt;
                </span>
                <span style="color:#4ade80;font-weight:700"><?= number_format($total, 2, ',', '.') ?> €</span>
            </div>
        </div>
        <div class="encomenda-body">
            <table style="width:100%">
                <thead>
                    <tr>
                        <th style="padding:7px 0;font-size:11px;color:var(--muted);text-transform:uppercase;border-bottom:1px solid var(--border)">Produto</th>
                        <th style="padding:7px 0;font-size:11px;color:var(--muted);text-transform:uppercase;border-bottom:1px solid var(--border)">Qtd.</th>
                        <th style="padding:7px 0;font-size:11px;color:var(--muted);text-transform:uppercase;border-bottom:1px solid var(--border)">Preço Unit.</th>
                        <th style="padding:7px 0;font-size:11px;color:var(--muted);text-transform:uppercase;border-bottom:1px solid var(--border)">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($itens as $item): ?>
                <tr>
                    <td style="padding:9px 0;border-bottom:1px solid var(--border);font-size:13px"><?= htmlspecialchars($item['produto']) ?></td>
                    <td style="padding:9px 0;border-bottom:1px solid var(--border);font-size:13px;color:var(--muted)"><?= $item['quantidade'] ?></td>
                    <td style="padding:9px 0;border-bottom:1px solid var(--border);font-size:13px;color:var(--muted)"><?= number_format($item['preco'], 2, ',', '.') ?> €</td>
                    <td style="padding:9px 0;border-bottom:1px solid var(--border);font-size:13px;font-weight:600"><?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?> €</td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

</main></div></div>
</body>
</html>
