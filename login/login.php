<?php
// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) session_start();

// Se o utilizador já está autenticado, redireciona para a loja
if (isset($_SESSION['user_id'])) {
    header("Location: ../loja/index.php");
    exit();
}

// Verifica se existe erro de autenticação vindo do autenticar.php
$erro = $_GET['erro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Driver Master</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="login-page">
    <div class="login-outer">

        <!-- Painel esquerdo com logo e descrição -->
        <div class="login-left">
            <img src="../imagensloja/icon_carrinho.png" width="56" height="56" alt="Carrinho">
            <h2>Driver Master</h2>
            <p>Faça login para aceder à loja e efetuar compras</p>
        </div>

        <!-- Painel direito com o formulário -->
        <div class="login-right">

            <!-- Link para voltar à loja sem fazer login -->
            <a href="../loja/index.php" style="font-size:12px;color:var(--muted);text-decoration:none;display:inline-block;margin-bottom:20px">← Voltar à Loja</a>

            <h3>Iniciar Sessão</h3>
            <p class="login-sub">Aceda à sua conta para comprar produtos</p>

            <!-- Mensagem de erro se o email ou senha estiverem incorretos -->
            <?php if ($erro === '1'): ?>
                <div class="alert alert-error">Email ou senha incorretos.</div>
            <?php endif; ?>

            <!-- Formulário de login, envia para autenticar.php -->
            <form action="autenticar.php" method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <div class="inp-wrap">
                        <!-- Ícone de email -->
                        <img src="../imagensloja/icon_email.png" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;pointer-events:none" alt="">
                        <input type="email" name="email" placeholder="email@exemplo.com" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <div class="inp-wrap">
                        <!-- Ícone de cadeado -->
                        <img src="../imagensloja/icon_cadeado.png" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);width:14px;height:14px;pointer-events:none" alt="">
                        <input type="password" name="senha" placeholder="A sua senha" required>
                    </div>
                </div>
                <button type="submit" class="login-btn">Entrar</button>
            </form>

            <!-- Link para a página de registo -->
            <div style="text-align:center;margin-top:14px;font-size:12px;color:var(--muted)">
                Não tem conta? <a href="registo.php" style="color:#4ade80;text-decoration:none">Registar aqui</a>
            </div>

        </div>
    </div>
</div>
</body>
</html>