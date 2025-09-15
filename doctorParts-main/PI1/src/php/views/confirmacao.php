<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../views/login.php");
    exit;
}

include '../classes/conexao.class.php';

$id_usuario = $_SESSION['usuario_id'];
if (!isset($_GET['pedido'])) {
    header("Location: ../views/carrinho.php");
    exit;
}

$id_pedido = intval($_GET['pedido']);

$conexao = new Conexao();
$db = $conexao->getConnection();

// Verificar se o pedido pertence ao usuário
$sqlCheck = "SELECT * FROM pedidos WHERE id_pedido = :id_pedido AND id_usuario = :id_usuario";
$stmtCheck = $db->prepare($sqlCheck);
$stmtCheck->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
$stmtCheck->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmtCheck->execute();
$pedido = $stmtCheck->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    echo "Pedido não encontrado ou acesso negado.";
    exit;
}

// Buscar itens do pedido EXATAMENTE com este id_pedido
$sqlItens = "
    SELECT ip.quantidade, ip.preco_unitario, p.nome
    FROM itens_pedido ip
    JOIN produtos p ON ip.id_produto = p.id_produto
    WHERE ip.id_pedido = :id_pedido
";
$stmtItens = $db->prepare($sqlItens);
$stmtItens->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
$stmtItens->execute();
$itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

// Buscar endereço do pedido
$sqlEndereco = "
    SELECT * FROM enderecos WHERE id_endereco = :id_endereco
";
$stmtEndereco = $db->prepare($sqlEndereco);
$stmtEndereco->bindParam(':id_endereco', $pedido['id_endereco'], PDO::PARAM_INT);
$stmtEndereco->execute();
$endereco = $stmtEndereco->fetch(PDO::FETCH_ASSOC);

// Caso não tenha complemento
$complemento = $endereco['complemento'] ?? 'Nenhum';

// Calcular total dos produtos
$totalProdutos = 0;
foreach ($itens as $item) {
    $totalProdutos += $item['preco_unitario'] * $item['quantidade'];
}

// Calcular valor do frete = total do pedido - total dos produtos
$valorFrete = $pedido['total'] - $totalProdutos;
if ($valorFrete < 0) {
    $valorFrete = 0; // segurança para não mostrar negativo
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Confirmação do Pedido</title>
    <link rel="stylesheet" href="../../css/styleConfirmacao.css"> <!-- se quiser estilizar -->
</head>
<body>
    <h1>Pedido Confirmado</h1>

    <h2>Detalhes do Pedido:</h2>
    <p><strong>Pedido Nº:</strong> <?= htmlspecialchars($pedido['id_pedido']) ?></p>
    <p><strong>Data do Pedido:</strong> <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($pedido['status']) ?></p>

    <h3>Itens:</h3>
    <ul>
        <?php foreach ($itens as $item): ?>
            <li>
                <?= htmlspecialchars($item['nome']) ?> - Quantidade: <?= (int)$item['quantidade'] ?> - Preço unitário: R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Resumo do Pagamento:</h3>
    <p><strong>Total Produtos:</strong> R$ <?= number_format($totalProdutos, 2, ',', '.') ?></p>
    <p><strong>Frete:</strong> R$ <?= number_format($valorFrete, 2, ',', '.') ?></p>
    <p><strong>Total Pago:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.') ?></p>

    <h2>Endereço de Entrega:</h2>
    <p>
        <?= htmlspecialchars($endereco['rua']) ?>, <?= htmlspecialchars($endereco['numero']) ?><br>
        <?= htmlspecialchars($endereco['bairro']) ?> - <?= htmlspecialchars($endereco['cidade']) ?>/<?= htmlspecialchars($endereco['estado']) ?><br>
        CEP: <?= htmlspecialchars($endereco['cep']) ?><br>
        Complemento: <?= htmlspecialchars($complemento) ?>
    </p>

    <h2>Dados do Pagamento:</h2>
    <p><strong>Forma:</strong> <?= htmlspecialchars($pedido['forma_pagamento']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($pedido['status']) ?></p>
    <p><strong>Data do Pagamento:</strong> <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>

    <p><a href="../views/index.php">Voltar à página inicial</a></p>
</body>
</html>
