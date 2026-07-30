<?php
// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) session_start();

// Se o utilizador já está autenticado, redireciona para a loja
if (isset($_SESSION['user_id'])) {
    header("Location: ../loja/index.php");
    exit();
}

// Ligação à base de dados
require_once("../includes/ligacao.php");

$erro    = '';
$sucesso = '';

// Processa o formulário quando é submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recolhe e limpa os dados do formulário
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $conf  = trim($_POST['confirmar'] ?? '');

    // Validação dos campos
    if ($nome === '' || $email === '' || $senha === '') {
        $erro = 'Por favor preencha todos os campos.';
    } elseif ($senha !== $conf) {
        $erro = 'As senhas não coincidem.';
    } elseif (strlen($senha) < 4) {
        $erro = 'A senha deve ter pelo menos 4 caracteres.';
    } else {
        // Verifica se o email já existe na base de dados
        $st = $conn->prepare("SELECT id FROM utilizadores WHERE email=?");
        $st->bind_param("s", $email);
        $st->execute();

        if ($st->get_result()->num_rows > 0) {
            $erro = 'Este email já está registado.';
        } else {
            // Encripta a senha com bcrypt antes de guardar
            $hash = password_hash($senha, PASSWORD_BCRYPT);

            // Insere o novo utilizador como 'cliente'
            $st2 = $conn->prepare("INSERT INTO utilizadores (nome, email, senha, tipo) VALUES (?, ?, ?, 'cliente')");
            $st2->bind_param("sss", $nome, $email, $hash);

            if ($st2->execute()) {
                $sucesso = 'Conta criada com sucesso! Pode agora fazer login.';
            } else {
                $erro = 'Erro ao criar conta: ' . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar - Driver Master</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="login-page">
    <div class="login-outer" style="width:640px">

        <!-- Painel esquerdo com logo e descrição -->
        <div class="login-left">
            <img src="../imagensloja/icon_registar.png" width="56" height="56" alt="Registar">
            <h2>Driver Master</h2>
            <p>Crie a sua conta e comece a comprar!</p>
        </div>

        <!-- Painel direito com o formulário -->
        <div class="login-right">

            <!-- Link para voltar à loja -->
            <a href="../loja/index.php" style="font-size:12px;color:var(--muted);text-decoration:none;display:inline-block;margin-bottom:20px">← Voltar à Loja</a>

            <h3>Criar Conta</h3>
            <p class="login-sub">Registe-se gratuitamente</p>

            <!-- Mensagem de erro -->
            <?php if ($erro): ?>
                <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <!-- Mensagem de sucesso após registo -->
            <?php if ($sucesso): ?>
                <div class="alert alert-success">● <?= htmlspecialchars($sucesso) ?></div>
                <p style="font-size:13px;color:var(--muted);margin-top:10px">
                    <a href="login.php" style="color:#4ade80">← Ir para o Login</a>
                </p>
            <?php else: ?>

            <!-- Formulário de registo -->
            <form action="registo.php" method="POST">
                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" name="nome" placeholder="O seu nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@exemplo.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" placeholder="Mínimo 4 caracteres" required>
                </div>
                <div class="form-group">
                    <label>Confirmar Senha</label>
                    <input type="password" name="confirmar" placeholder="Repita a senha" required>
                </div>
                <button type="submit" class="login-btn">Criar Conta</button>
            </form>

            <?php endif; ?>

            <!-- Link para a página de login -->
            <div style="text-align:center;margin-top:14px;font-size:12px;color:var(--muted)">
                Já tem conta? <a href="login.php" style="color:#4ade80;text-decoration:none">Fazer Login</a>
            </div>

        </div>
    </div>
</div>
</body>
</html>