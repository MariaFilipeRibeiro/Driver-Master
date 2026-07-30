<?php
/* Criação dos utilizadores de teste */

require_once('includes/ligacao.php');

$utilizadores = [
    ['Administrador', 'admin@loja.com',   'admin',  'admin'],
    ['Diogo Dias',    'diogo@gmail.com', 'diogodias', 'cliente'],
    ['Maria Ribeiro',  'maria@gmail.com',  'mariaribeiro', 'cliente'],
];


$criados = 0;
$existentes = 0;

foreach ($utilizadores as [$nome, $email, $senha, $tipo]) {
    // Verificar se já existe
    $st = $conn->prepare("SELECT id FROM utilizadores WHERE email=?");
    $st->bind_param("s", $email);
    $st->execute();

    if ($st->get_result()->num_rows > 0) {
        // Atualizar a senha para garantir que está correta
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        $st2 = $conn->prepare("UPDATE utilizadores SET senha=?, tipo=? WHERE email=?");
        $st2->bind_param("sss", $hash, $tipo, $email);
        $st2->execute();
        echo "<p class='warn'>Utilizador <strong>$email</strong> já existe — senha atualizada para <code>$senha</code></p>";
        $existentes++;
    } else {
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        $st2 = $conn->prepare("INSERT INTO utilizadores (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        $st2->bind_param("ssss", $nome, $email, $hash, $tipo);
        if ($st2->execute()) {
            echo "<p class='ok'>Criado: <strong>$nome</strong> ($email) — senha: <code>$senha</code> — tipo: <strong>$tipo</strong></p>";
            $criados++;
        } else {
            echo "<p class='err'>Erro ao criar $email: " . htmlspecialchars($conn->error) . "</p>";
        }
    }
}

?>
