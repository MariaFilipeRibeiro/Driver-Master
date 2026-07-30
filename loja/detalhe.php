<?php
// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) session_start();

// Ligação à base de dados
require_once("../includes/ligacao.php");

// Define a página ativa na navbar
$active_loja = 'produtos';

// Obtém o ID do produto a partir do URL e valida
$id = (int)($_GET['id'] ?? 0);
// Se não existir ID válido, redireciona para a lista de produtos
if (!$id) { header("Location: produtos.php"); exit(); }

// PROCESSAR ADIÇÃO AO CARRINHO
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_carrinho'])) {

    // Se não estiver autenticado, redireciona para o login
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login/login.php");
        exit();
    }

    $user_id = (int)$_SESSION['user_id'];
    // Garante que a quantidade é pelo menos 1
    $qty     = max(1, (int)($_POST['quantidade'] ?? 1));

    // Verifica se o produto já existe no carrinho do utilizador
    $st = $conn->prepare("SELECT id, quantidade FROM carrinho WHERE user_id=? AND produto_id=? AND comprado=0");
    $st->bind_param("ii", $user_id, $id);
    $st->execute();
    $ex = $st->get_result()->fetch_assoc();

    if ($ex) {
        // Se já existe, soma a quantidade ao valor atual
        $nova_qty = $ex['quantidade'] + $qty;
        $conn->query("UPDATE carrinho SET quantidade=$nova_qty WHERE id={$ex['id']}");
    } else {
        // Se não existe, cria um novo registo no carrinho
        $conn->query("INSERT INTO carrinho (user_id, produto_id, quantidade) VALUES ($user_id, $id, $qty)");
    }
    $msg = 'ok';
}

// BUSCAR DADOS DO PRODUTO 
// Busca o produto com o nome da categoria associada
$stmt = $conn->prepare("SELECT p.*, c.nome as cat_nome FROM produtos p LEFT JOIN categorias c ON p.categoria_id=c.id WHERE p.id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();

// Se o produto não existir, redireciona para a lista
if (!$p) { header("Location: produtos.php"); exit(); }

$stock = (int)$p['stock'];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($p['nome']) ?> - Driver Master</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../loja.css">
</head>
<body style="background:var(--bg);color:var(--text)">
<?php include "../includes/navbar_loja.php"; ?>

<div class="loja-main">

    <!-- Mensagem de sucesso após adicionar ao carrinho -->
    <?php if ($msg === 'ok'): ?>
        <div class="alert alert-success" style="margin-bottom:16px">● Produto adicionado ao carrinho! <a href="carrinho.php" style="color:#4ade80">Ver carrinho</a></div>
    <?php endif; ?>

    <!-- Link para voltar à lista (mantém o filtro de categoria se existir) -->
    <div style="margin-bottom:16px">
        <a href="produtos.php<?= $p['categoria_id'] ? '?categoria='.$p['categoria_id'] : '' ?>" style="font-size:13px;color:var(--muted);text-decoration:none">← Voltar aos Produtos</a>
    </div>

    <div class="card">
        <div class="produto-detalhe">

            <!-- Imagem do produto ou placeholder se não tiver imagem -->
            <div>
                <?php if ($p['imagem'] && file_exists("../imagens/" . $p['imagem'])): ?>
                    <img src="../imagens/<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>">
                <?php else: ?>
                    <div style="width:100%;height:280px;background:var(--surface2);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:13px">Sem imagem</div>
                <?php endif; ?>
            </div>

            <div class="detalhe-info">

                <!-- Badge com o nome da categoria -->
                <?php if ($p['cat_nome']): ?>
                    <span class="badge badge-green" style="margin-bottom:10px;display:inline-block"><?= htmlspecialchars($p['cat_nome']) ?></span>
                <?php endif; ?>

                <!-- Nome e preço do produto -->
                <h1 style="font-size:24px;font-weight:700;margin-bottom:10px"><?= htmlspecialchars($p['nome']) ?></h1>
                <div class="preco-grande" style="font-size:28px;font-weight:700;color:#4ade80;margin-bottom:16px">
                    <?= number_format($p['preco'], 2, ',', '.') ?> €
                </div>

                <!-- Descrição do produto (se existir) -->
                <?php if ($p['descricao']): ?>
                <p style="color:var(--muted);font-size:14px;line-height:1.7;margin-bottom:20px"><?= nl2br(htmlspecialchars($p['descricao'])) ?></p>
                <?php endif; ?>

                <!-- Indicador de stock: esgotado, baixo ou disponível -->
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:24px">
                    <span style="font-size:13px;color:var(--muted)">Stock:</span>
                    <?php if ($stock <= 0): ?>
                        <span class="stock-badge stock-out">Esgotado</span>
                    <?php elseif ($stock <= 3): ?>
                        <span class="stock-badge stock-low">Apenas <?= $stock ?> em stock</span>
                    <?php else: ?>
                        <span class="stock-badge stock-ok"><?= $stock ?> em stock</span>
                    <?php endif; ?>
                </div>

                <?php if ($stock > 0): ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Formulário para adicionar ao carrinho (só para utilizadores autenticados) -->
                    <form method="post" style="display:flex;gap:10px;align-items:center">
                        <div class="form-group" style="margin:0;width:100px">
                            <label style="font-size:11px">Quantidade</label>
                            <!-- max limita a quantidade ao stock disponível -->
                            <input type="number" name="quantidade" value="1" min="1" max="<?= $stock ?>">
                        </div>
                        <div style="margin-top:16px">
                            <button type="submit" name="add_carrinho" class="btn btn-primary">
                                Adicionar ao Carrinho
                            </button>
                        </div>
                    </form>
                    <?php else: ?>
                    <!-- Aviso para fazer login caso não esteja autenticado -->
                    <div style="margin-top:16px;background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.25);color:#60a5fa;padding:11px 16px;border-radius:8px;font-size:13px;font-weight:500">
                        <a href="../login/login.php" style="color:#93c5fd">Faça login</a> para adicionar ao carrinho.
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Mensagem de produto esgotado -->
                    <div class="alert alert-error">Este produto está esgotado.</div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
</body>
</html>