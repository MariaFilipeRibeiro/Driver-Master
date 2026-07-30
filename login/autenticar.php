<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once("../includes/ligacao.php");

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if ($email === '' || $senha === '') {
    header("Location: login.php?erro=1");
    exit();
}

$stmt = $conn->prepare("SELECT id, nome, email, senha, tipo FROM utilizadores WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if (password_verify($senha, $row['senha'])) {
        $_SESSION['user_id']   = $row['id'];
        $_SESSION['user_nome'] = $row['nome'];
        $_SESSION['user_email']= $row['email'];
        $_SESSION['user_tipo'] = $row['tipo'];

        if ($row['tipo'] === 'admin') {
            header("Location: ../admin/index.php");
        } else {
            header("Location: ../loja/index.php");
        }
        exit();
    }
}

header("Location: login.php?erro=1");
exit();
?>
