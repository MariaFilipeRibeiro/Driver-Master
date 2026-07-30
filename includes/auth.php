<?php
// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) session_start();

// Verifica se o utilizador está autenticado
// Se não estiver autenticado, redireciona para o login
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.php");
    exit();
}
?>