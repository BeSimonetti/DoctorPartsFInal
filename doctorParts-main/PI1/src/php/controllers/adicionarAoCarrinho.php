<?php
session_start();
include '../classes/conexao.class.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../views/login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$id_produto = isset($_POST['id_produto']) ? (int)$_POST['id_produto'] : null;
$quantidade = isset($_POST['quantidade']) ? (int)$_POST['quantidade'] : 1;
if ($quantidade < 1) $quantidade = 1;

if (!$id_produto) {
    header("Location: ../views/index.php");
    exit;
}

$conexao = new Conexao();
$db = $conexao->getConnection();

$sql = "SELECT * FROM carrinho WHERE id_usuario = :id_usuario AND id_produto = :id_produto";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario);
$stmt->bindParam(':id_produto', $id_produto);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($item) {
    $novaQuantidade = $item['quantidade'] + $quantidade;
    $sql = "UPDATE carrinho SET quantidade = :quantidade WHERE id_carrinho = :id_carrinho";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':quantidade', $novaQuantidade);
    $stmt->bindParam(':id_carrinho', $item['id_carrinho']);
    $stmt->execute();
} else {
    $sql = "INSERT INTO carrinho (id_usuario, id_produto, quantidade, data_adicionado) 
            VALUES (:id_usuario, :id_produto, :quantidade, NOW())";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':id_produto', $id_produto);
    $stmt->bindParam(':quantidade', $quantidade);
    $stmt->execute();
}

$redirect = $_SERVER['HTTP_REFERER'] ?? '../views/index.php';
header("Location: $redirect");
exit;
