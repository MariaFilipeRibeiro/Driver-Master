<?php
require_once("../includes/auth_admin.php");
require_once("../includes/ligacao.php");

$id   = intval($_GET['id'] ?? 0);
$nome = $_GET['nome'] ?? 'Produto';

if (!$id) { header("Location: produtos.php"); exit(); }

// Verificar se tem itens no carrinho (vendidos)
$check = $conn->query("SELECT COUNT(*) as n FROM carrinho WHERE produto_id=$id AND comprado=1")->fetch_assoc()['n'];

if ($check > 0) {
    header("Location: produtos.php?ok=" . urlencode("Não é possível eliminar \"$nome\" pois existem encomendas associadas."));
    exit();
}

// Buscar imagem para apagar
$row = $conn->query("SELECT imagem FROM produtos WHERE id=$id")->fetch_assoc();
if ($row && $row['imagem'] && file_exists("../imagens/" . $row['imagem'])) {
    unlink("../imagens/" . $row['imagem']);
}

// Apagar do carrinho (itens não comprados) e depois o produto
$conn->query("DELETE FROM carrinho WHERE produto_id=$id AND comprado=0");
$stmt = $conn->prepare("DELETE FROM produtos WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: produtos.php?ok=" . urlencode("Produto \"$nome\" eliminado com sucesso."));
} else {
    header("Location: produtos.php?ok=" . urlencode("Erro: " . $conn->error));
}
exit();
?>
