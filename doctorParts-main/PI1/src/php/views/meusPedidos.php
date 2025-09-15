<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include '../classes/conexao.class.php';

$id_usuario = $_SESSION['usuario_id'];

$conexao = new Conexao();
$db = $conexao->getConnection();

// Buscar pedidos do usuário
$sql = "
    SELECT * FROM pedidos 
    WHERE id_usuario = :id_usuario 
    ORDER BY data_pedido DESC
";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Pedidos</title>
    <link rel="stylesheet" href="../../css/styleMeusPedidos.css">
</head>
<body>

    <div class="container-pedidos">
        <!-- Botão voltar -->
        <a href="areaUsuario.php" class="botao-voltar">&larr; Voltar</a>

        <h1>Meus Pedidos</h1>

        <?php if (count($pedidos) === 0): ?>
            <p>Você ainda não fez nenhum pedido.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nº Pedido</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Pagamento</th>
                        <th>Total</th>
                        <th>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($pedido['id_pedido']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                            <td><?= htmlspecialchars($pedido['status']) ?></td>
                            <td><?= ucfirst(htmlspecialchars($pedido['forma_pagamento'])) ?></td>
                            <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                            <td>
                                <a href="confirmacao.php?pedido=<?= $pedido['id_pedido'] ?>" class="botao-detalhes">
                                    Ver detalhes
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</body>
</html>
