<?php
// Define a página ativa na sidebar (usado para destacar o item atual)
$active_admin = $active_admin ?? '';
// Caminho relativo para os ficheiros (varia conforme a pasta onde está a página)
$depth_admin  = $depth_admin  ?? '../';
?>

<!-- Wrapper principal que envolve navbar + sidebar + conteúdo -->
<div class="layout-wrapper">
<nav class="navbar">
    <div class="nav-left">
        <!-- Logo da loja como imagem com link para o dashboard -->
        <a class="brand" href="<?= $depth_admin ?>admin/index.php">
            <img src="<?= $depth_admin ?>logo.png" alt="Driver Master" style="height:46px;object-fit:contain">
        </a>
    </div>
    <div class="nav-right">
        <div class="user-info">
            <!-- Ícone e nome do utilizador autenticado -->
            <img src="<?= $depth_admin ?>imagensloja/icon_utilizador.png" width="15" height="15" alt="">
            <span class="username"><?= htmlspecialchars($_SESSION['user_nome'] ?? 'Admin') ?></span>
            <!-- Link para voltar à loja (frontoffice) -->
            <a href="<?= $depth_admin ?>loja/index.php">
                <img src="<?= $depth_admin ?>imagensloja/icon_loja.png" width="14" height="14" alt="">
                Loja
            </a>
            <!-- Link para terminar sessão -->
            <a href="<?= $depth_admin ?>login/logout.php">
                <img src="<?= $depth_admin ?>imagensloja/icon_sair.png" width="14" height="14" alt="">
                Sair
            </a>
        </div>
    </div>
</nav>
<div class="body-layout">
<aside class="sidebar">
    <!-- Dashboard — página inicial do painel admin -->
    <a class="nav-item <?= $active_admin === 'dashboard' ? 'active' : '' ?>" href="<?= $depth_admin ?>admin/index.php">
        <img src="<?= $depth_admin ?>imagensloja/icon_dashboard.png" width="16" height="16" alt="">
        Dashboard
    </a>
    <!-- Gestão de produtos (listar, criar, editar, eliminar) -->
    <a class="nav-item <?= $active_admin === 'produtos' ? 'active' : '' ?>" href="<?= $depth_admin ?>admin/produtos.php">
        <img src="<?= $depth_admin ?>imagensloja/icon_produtos.png" width="16" height="16" alt="">
        Produtos
    </a>
    <!-- Gestão de categorias (listar, criar, editar, eliminar) -->
    <a class="nav-item <?= $active_admin === 'categorias' ? 'active' : '' ?>" href="<?= $depth_admin ?>admin/categorias.php">
        <img src="<?= $depth_admin ?>imagensloja/icon_categorias.png" width="16" height="16" alt="">
        Categorias
    </a>
    <!-- Consulta de todas as encomendas realizadas -->
    <a class="nav-item <?= $active_admin === 'encomendas' ? 'active' : '' ?>" href="<?= $depth_admin ?>admin/encomendas.php">
        <img src="<?= $depth_admin ?>imagensloja/icon_encomendas.png" width="16" height="16" alt="">
        Encomendas
    </a>
    <!-- Gestão de utilizadores (editar, eliminar, alterar tipo) -->
    <a class="nav-item <?= $active_admin === 'utilizadores' ? 'active' : '' ?>" href="<?= $depth_admin ?>admin/utilizadores.php">
        <img src="<?= $depth_admin ?>imagensloja/icon_utilizadores_admin.png" width="16" height="16" alt="">
        Utilizadores
    </a>
</aside>
<!-- Conteúdo principal da página -->
<main class="main-content">