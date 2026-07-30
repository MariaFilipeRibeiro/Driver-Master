<?php
// Verifica se o utilizador está autenticado e é administrador
// Se não for, redireciona para o login
require_once("../includes/auth_admin.php");

// Ligação à base de dados
require_once("../includes/ligacao.php");

// Define a página ativa na sidebar
$active_admin = 'utilizadores';
$depth_admin  = '../';

// ELIMINAR UTILIZADOR
if (isset($_GET['apagar'])) {
    $uid = (int)$_GET['apagar'];

    // Não permite que o admin elimine a própria conta
    if ($uid !== (int)$_SESSION['user_id']) {
        // Apaga primeiro o carrinho para não violar a foreign key da tabela
        $conn->query("DELETE FROM carrinho WHERE user_id=$uid");
        // Apaga o utilizador da base de dados
        $conn->query("DELETE FROM utilizadores WHERE id=$uid");
    }
    header("Location: utilizadores.php");
    exit();
}

// Guarda os dados do utilizador a editar (null se não estiver editado)
$editar_row = null;
$erro       = '';
$sucesso    = '';

// Quando se clica no botão Editar, carrega os dados do utilizador
if (isset($_GET['editar'])) {
    $uid = (int)$_GET['editar'];
    $editar_row = $conn->query("SELECT * FROM utilizadores WHERE id=$uid")->fetch_assoc();
}

// Quando o formulário é submetido, valida e guarda as alterações
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $uid   = (int)$_POST['id'];
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    // Garante que o tipo só pode ser 'admin' ou 'cliente'
    $tipo  = $_POST['tipo'] === 'admin' ? 'admin' : 'cliente';
    $senha = trim($_POST['senha'] ?? '');

    // Validação: nome e email são obrigatórios
    if ($nome === '' || $email === '') {
        $erro = 'Nome e email são obrigatórios.';
        // Recarrega os dados para manter o formulário aberto
        $editar_row = $conn->query("SELECT * FROM utilizadores WHERE id=$uid")->fetch_assoc();
    } else {
        if ($senha !== '') {
            // Nova senha fornecida — encripta com bcrypt antes de guardar
            $hash = password_hash($senha, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE utilizadores SET nome=?, email=?, tipo=?, senha=? WHERE id=?");
            $stmt->bind_param("ssssi", $nome, $email, $tipo, $hash, $uid);
        } else {
            // Sem nova senha — mantém a senha atual na base de dados
            $stmt = $conn->prepare("UPDATE utilizadores SET nome=?, email=?, tipo=? WHERE id=?");
            $stmt->bind_param("sssi", $nome, $email, $tipo, $uid);
        }

        if ($stmt->execute()) {
            $sucesso = "Utilizador \"$nome\" atualizado com sucesso!";
        } else {
            $erro = 'Erro: ' . $conn->error;
            // Mantém o formulário aberto em caso de erro
            $editar_row = $conn->query("SELECT * FROM utilizadores WHERE id=$uid")->fetch_assoc();
        }
    }
}

// Pesquisa todos os utilizadores com o número de encomendas realizadas
// COUNT DISTINCT agrupa por data/minuto para contar encomendas
$res = $conn->query("
    SELECT u.*, 
           COUNT(DISTINCT DATE_FORMAT(c.data, '%Y-%m-%d %H:%i')) as total_encs
    FROM utilizadores u
    LEFT JOIN carrinho c ON c.user_id=u.id AND c.comprado=1
    GROUP BY u.id
    ORDER BY u.nome
");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilizadores - Gerir</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include "../includes/navbar_admin.php"; ?>

<!-- Mensagem de erro (campos inválidos ou erro de base de dados) -->
<?php if ($erro): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<!-- Mensagem de sucesso após guardar alterações -->
<?php if ($sucesso): ?>
    <div class="alert alert-success">● <?= htmlspecialchars($sucesso) ?></div>
<?php endif; ?>

<div class="page-header">
    <h1>Utilizadores</h1>
</div>

<!-- Formulário de edição (só aparece quando se clica em Editar) -->
<?php if ($editar_row): ?>
<div class="card" style="margin-bottom:24px">
    <h3 style="font-size:14px;font-weight:700;margin-bottom:16px;color:var(--muted)">EDITAR UTILIZADOR</h3>
    <form method="post">
        <!-- ID do utilizador a editar enviado como campo oculto -->
        <input type="hidden" name="id" value="<?= $editar_row['id'] ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Nome Completo</label>
                <!-- Pré-preenche com os dados atuais do utilizador -->
                <input type="text" name="nome" value="<?= htmlspecialchars($editar_row['nome']) ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($editar_row['email']) ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Tipo</label>
                <!-- Dropdown para definir se é cliente ou administrador -->
                <select name="tipo">
                    <option value="cliente" <?= $editar_row['tipo'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                    <option value="admin"   <?= $editar_row['tipo'] === 'admin'   ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label>Nova Senha <small style="color:var(--muted)">(deixar em branco para manter)</small></label>
                <!-- Campo opcional — se ficar vazio a senha não é alterada -->
                <input type="password" name="senha" placeholder="Nova senha">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" name="guardar" class="btn btn-editar">Guardar Alterações</button>
            <!-- Cancelar fecha o formulário sem guardar -->
            <a href="utilizadores.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Tabela com a lista de todos os utilizadores registados -->
<div class="table-wrapper">
<?php if ($res->num_rows === 0): ?>
    <div class="empty-state"><p>Nenhum utilizador.</p></div>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Encomendas</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php while($u = $res->fetch_assoc()): ?>
    <tr>
        <td><?= $u['id'] ?></td>
        <td>
            <?= htmlspecialchars($u['nome']) ?>
            <!-- Marca visualmente a conta do admin que está autenticado -->
            <?php if ($u['id'] == $_SESSION['user_id']): ?>
                <span class="badge badge-blue" style="margin-left:6px;font-size:9px">Tu</span>
            <?php endif; ?>
        </td>
        <td style="color:var(--muted)"><?= htmlspecialchars($u['email']) ?></td>
        <td>
            <!-- Badge laranja para admin, verde para cliente -->
            <?php if ($u['tipo'] === 'admin'): ?>
                <span class="badge" style="background:rgba(249,115,22,.15);color:#fb923c">Admin</span>
            <?php else: ?>
                <span class="badge badge-green">Cliente</span>
            <?php endif; ?>
        </td>
        <!-- Número total de encomendas realizadas pelo utilizador -->
        <td><span class="badge badge-blue"><?= $u['total_encs'] ?></span></td>
        <td style="display:flex;gap:6px">
            <!-- Não mostra ações para a própria conta (evita auto-eliminação) -->
            <?php if ($u['id'] != $_SESSION['user_id']): ?>
            <!-- Abre o formulário de edição com os dados do utilizador -->
            <a href="utilizadores.php?editar=<?= $u['id'] ?>" class="btn btn-editar btn-sm">Editar</a>
            <!-- Elimina o utilizador após confirmação -->
            <a href="utilizadores.php?apagar=<?= $u['id'] ?>" class="btn btn-eliminar btn-sm" onclick="return confirm('Eliminar este utilizador?')">Eliminar</a>
            <?php else: ?>
            <!-- Traço para indicar que não há ações disponíveis para a própria conta -->
            <span style="color:var(--muted);font-size:12px">—</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php endif; ?>
</div>

</main></div></div>
</body>
</html>