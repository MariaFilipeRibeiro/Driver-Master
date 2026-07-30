<?php
// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) session_start();

// Verifica se o utilizador está autenticado e se é administrador
// Se não estiver autenticado ou não for admin, redireciona para o login
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: /login/login.php");
    exit();
}
?>