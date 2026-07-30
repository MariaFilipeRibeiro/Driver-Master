<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}
require_once("../includes/ligacao.php");
$active_loja = 'carrinho';
$user_id = (int)$_SESSION['user_id'];

// Verificar se há itens no carrinho
$res = $conn->query("
    SELECT c.quantidade, p.preco, p.nome, p.stock, c.produto_id, c.id as cid
    FROM carrinho c JOIN produtos p ON c.produto_id=p.id
    WHERE c.user_id=$user_id AND c.comprado=0
");

$itens = [];
$total = 0;
while ($r = $res->fetch_assoc()) {
    $itens[] = $r;
    $total += $r['preco'] * $r['quantidade'];
}

if (empty($itens)) {
    header("Location: carrinho.php");
    exit();
}

$confirmado = false;
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
    // Verificar stock antes de finalizar
    $ok = true;
    foreach ($itens as $item) {
        if ($item['quantidade'] > $item['stock']) {
            $erro = "Stock insuficiente para \"" . htmlspecialchars($item['nome']) . "\".";
            $ok = false;
            break;
        }
    }
    if ($ok) {
        // Atualizar stock e marcar como comprado
        foreach ($itens as $item) {
            $novo_stock = $item['stock'] - $item['quantidade'];
            $conn->query("UPDATE produtos SET stock=$novo_stock WHERE id={$item['produto_id']}");
        }
        $conn->query("UPDATE carrinho SET comprado=1, data=NOW() WHERE user_id=$user_id AND comprado=0");
        $confirmado = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - Driver Master</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../loja.css">
</head>
<body style="background:var(--bg);color:var(--text)">
<?php include "../includes/navbar_loja.php"; ?>

<div class="loja-main" style="max-width:600px">

    <?php if ($confirmado): ?>
        <div style="text-align:center;padding:40px 20px">
            <div style="font-size:60px;margin-bottom:20px">✅</div>
            <h1 style="font-size:24px;font-weight:700;margin-bottom:10px">Compra Realizada!</h1>
            <p style="color:var(--muted);margin-bottom:24px">A sua encomenda foi registada com sucesso. Obrigado pela sua compra!</p>
            <div style="display:flex;gap:12px;justify-content:center">
                <a href="encomendas.php" class="btn btn-primary">Ver Encomendas</a>
                <a href="index.php" class="btn btn-secondary">Voltar à Loja</a>
            </div>
        </div>

    <?php else: ?>
        <div class="page-header">
            <h1>Confirmar Compra</h1>
            <a href="carrinho.php" class="btn btn-secondary">← Voltar ao Carrinho</a>
        </div>

        <?php if ($erro): ?>
            <div class="alert alert-error"><?= $erro ?></div>
        <?php endif; ?>

        <div class="card">
            <h3 style="font-size:14px;font-weight:700;margin-bottom:16px;color:var(--muted)">RESUMO DA ENCOMENDA</h3>
            <table>
                <thead>
                    <tr><th>Produto</th><th>Qtd.</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                <?php foreach ($itens as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nome']) ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td><?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?> €</td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div style="padding:14px 16px 0;text-align:right;font-size:16px;font-weight:700;color:#4ade80;border-top:1px solid var(--border);margin-top:8px">
                Total: <?= number_format($total, 2, ',', '.') ?> €
            </div>
        </div>

        <form method="post">
            <div class="alert alert-info" style="margin-bottom:16px">
                ℹ Ao confirmar, a encomenda será registada e o stock atualizado.
            </div>
            <div style="display:flex;gap:10px">
                <button type="submit" name="confirmar" class="btn btn-primary" style="flex:1;justify-content:center;padding:12px">
                    ✓ Confirmar Encomenda
                </button>
                <a href="carrinho.php" class="btn btn-secondary" style="flex:1;justify-content:center;padding:12px">Cancelar</a>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
