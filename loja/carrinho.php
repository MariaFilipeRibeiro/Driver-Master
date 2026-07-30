<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}
require_once("../includes/ligacao.php");
$active_loja = 'carrinho';
$user_id = (int)$_SESSION['user_id'];

// Remover produto
if (isset($_POST['remover'])) {
    $cid = (int)$_POST['carrinho_id'];
    $conn->query("DELETE FROM carrinho WHERE id=$cid AND user_id=$user_id AND comprado=0");
    header("Location: carrinho.php");
    exit();
}

// Atualizar quantidade
if (isset($_POST['atualizar'])) {
    $cid = (int)$_POST['carrinho_id'];
    $qty = max(1, (int)$_POST['quantidade']);
    $conn->query("UPDATE carrinho SET quantidade=$qty WHERE id=$cid AND user_id=$user_id AND comprado=0");
    header("Location: carrinho.php");
    exit();
}

// Buscar produtos do carrinho
$res = $conn->query("
    SELECT c.id as carrinho_id, c.quantidade, p.id as produto_id, p.nome, p.preco, p.stock, p.imagem
    FROM carrinho c
    JOIN produtos p ON c.produto_id = p.id
    WHERE c.user_id=$user_id AND c.comprado=0
    ORDER BY c.id
");

$total = 0;
$itens = [];
while ($row = $res->fetch_assoc()) {
    $total += $row['preco'] * $row['quantidade'];
    $itens[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Driver Master</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../loja.css">
</head>
<body style="background:var(--bg);color:var(--text)">
<?php include "../includes/navbar_loja.php"; ?>

<div class="loja-main">
    <div class="page-header">
        <h1>Meu Carrinho</h1>
        <a href="produtos.php" class="btn btn-secondary">← Continuar a Comprar</a>
    </div>

    <?php if (empty($itens)): ?>
        <div class="alert alert-info">O seu carrinho está vazio. <a href="produtos.php" style="color:#93c5fd">Ver produtos</a></div>
    <?php else: ?>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço Unit.</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($itens as $c): ?>
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px">
                        <?php if ($c['imagem'] && file_exists("../imagens/" . $c['imagem'])): ?>
                            <img src="../imagens/<?= htmlspecialchars($c['imagem']) ?>" width="40" height="40" style="object-fit:cover;border-radius:5px;border:1px solid var(--border)">
                        <?php endif; ?>
                        <a href="detalhe.php?id=<?= $c['produto_id'] ?>" style="color:var(--text);text-decoration:none;font-weight:500"><?= htmlspecialchars($c['nome']) ?></a>
                    </div>
                </td>
                <td><?= number_format($c['preco'], 2, ',', '.') ?> €</td>
                <td>
                    <form method="post" style="display:flex;gap:6px;align-items:center">
                        <input type="hidden" name="carrinho_id" value="<?= $c['carrinho_id'] ?>">
                        <input type="number" name="quantidade" value="<?= $c['quantidade'] ?>" min="1" max="<?= $c['stock'] ?>" style="width:60px;padding:5px 8px;background:var(--surface2);border:1px solid var(--border);border-radius:6px;color:var(--text);font-size:13px">
                        <button type="submit" name="atualizar" class="btn btn-secondary btn-sm">OK</button>
                    </form>
                </td>
                <td style="font-weight:600;color:#4ade80"><?= number_format($c['preco'] * $c['quantidade'], 2, ',', '.') ?> €</td>
                <td>
                    <form method="post">
                        <input type="hidden" name="carrinho_id" value="<?= $c['carrinho_id'] ?>">
                        <button type="submit" name="remover" class="btn btn-eliminar btn-sm">Remover</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="carrinho-total">
        <div>
            <div style="font-size:12px;color:var(--muted);margin-bottom:4px">Total da Compra</div>
            <div class="total-valor"><?= number_format($total, 2, ',', '.') ?> €</div>
        </div>
        <a href="finalizar.php" class="btn btn-primary" style="font-size:15px;padding:12px 28px">
            ✓ Finalizar Compra
        </a>
    </div>

    <?php endif; ?>
</div>
</body>
</html>
