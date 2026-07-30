<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Contar itens no carrinho (só para utilizadores autenticados)
$carrinho_count = 0;
if (isset($_SESSION['user_id']) && isset($conn)) {
    $uid = (int)$_SESSION['user_id'];
    $res_c = $conn->query("SELECT SUM(quantidade) as total FROM carrinho WHERE user_id=$uid AND comprado=0");
    if ($res_c) {
        $row_c = $res_c->fetch_assoc();
        $carrinho_count = (int)($row_c['total'] ?? 0);
    }
}

$active_loja = $active_loja ?? '';
?>
<nav class="loja-nav">
    <!-- Logo da loja como imagem (evita problemas de fontes entre browsers) -->
    <a class="brand" href="/loja/index.php">
        <img src="../logo.png" alt="Driver Master" style="height:46px;object-fit:contain">
    </a>
    <div class="nav-links">
        <!-- Link para a página inicial -->
        <a href="/loja/index.php" class="<?= $active_loja === 'inicio' ? 'active' : '' ?>">
            <img src="../imagensloja/icon_inicio.png" width="14" height="14" alt="">
            <span>Início</span>
        </a>
        <!-- Link para a lista de produtos -->
        <a href="/loja/produtos.php" class="<?= $active_loja === 'produtos' ? 'active' : '' ?>">
            <img src="../imagensloja/icon_produtos.png" width="14" height="14" alt="">
            <span>Produtos</span>
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Link para o carrinho com contador de itens -->
        <a href="/loja/carrinho.php" class="carrinho-link <?= $active_loja === 'carrinho' ? 'active' : '' ?>">
            <img src="../imagensloja/icon_carrinho.png" width="14" height="14" alt="">
            <span>Carrinho</span>
            <?php if ($carrinho_count > 0): ?>
                <span class="carrinho-count"><?= $carrinho_count ?></span>
            <?php endif; ?>
        </a>
        <!-- Link para o histórico de encomendas -->
        <a href="/loja/encomendas.php" class="<?= $active_loja === 'encomendas' ? 'active' : '' ?>">
            <img src="../imagensloja/icon_encomendas.png" width="14" height="14" alt="">
            <span>Encomendas</span>
        </a>
        <?php if ($_SESSION['user_tipo'] === 'admin'): ?>
        <!-- Link para o painel de administração (só visível para admins) -->
        <a href="/admin/index.php" style="color:#fb923c">
            <img src="../imagensloja/icon_admin.png" width="14" height="14" alt="">
            <span>Gerir</span>
        </a>
        <?php endif; ?>
        <!-- Botão de logout com nome do utilizador autenticado -->
        <a href="/login/logout.php" style="color:var(--red)">
            <img src="../imagensloja/icon_sair.png" width="14" height="14" alt="">
            <span><?= htmlspecialchars("Sair") ?></span>
        </a>
        <?php else: ?>
        <!-- Link para o login (só visível para visitantes) -->
        <a href="/login/login.php" class="<?= $active_loja === 'login' ? 'active' : '' ?>">
            <img src="../imagensloja/icon_login.png" width="14" height="14" alt="">
            <span>Login</span>
        </a>
        <!-- Link para o registo de nova conta -->
        <a href="/login/registo.php">
            <img src="../imagensloja/icon_registar.png" width="14" height="14" alt="">
            <span>Registar</span>
        </a>
        <?php endif; ?>
    </div>
</nav>