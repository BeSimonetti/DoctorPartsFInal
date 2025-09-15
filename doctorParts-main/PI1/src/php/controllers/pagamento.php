<?php
session_start();
include '../classes/conexao.class.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: finalizar.php?erro=metodo_invalido');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$forma_pagamento = isset($_POST['forma_pagamento']) ? $_POST['forma_pagamento'] : null;
$id_endereco = isset($_POST['id_endereco']) ? intval($_POST['id_endereco']) : null;
$frete = isset($_POST['frete']) ? $_POST['frete'] : null;

if (!$forma_pagamento || !$id_endereco || !$frete) {
    header('Location: finalizar.php?erro=parametros_invalidos');
    exit;
}

// Mapear valores dos fretes, igual no finalizar.php
$fretes = [
    'sedex' => 20.00,
    'pac' => 15.00,
    'retirar' => 0.00
];

if (!array_key_exists($frete, $fretes)) {
    header('Location: finalizar.php?erro=frete_invalido');
    exit;
}

$valorFrete = $fretes[$frete];

try {
    $conexao = new Conexao();
    $db = $conexao->getConnection();

    // Buscar itens do carrinho para montar o pedido
    $sqlItens = "
        SELECT c.id_produto, c.quantidade, p.preco
        FROM carrinho c
        JOIN produtos p ON c.id_produto = p.id_produto
        WHERE c.id_usuario = :id_usuario
    ";
    $stmtItens = $db->prepare($sqlItens);
    $stmtItens->bindParam(':id_usuario', $id_usuario);
    $stmtItens->execute();
    $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

    if (count($itens) === 0) {
        header("Location: carrinho.php?msg=vazio");
        exit;
    }

    // Calcular total dos produtos
    $totalProdutos = 0;
    foreach ($itens as $item) {
        $totalProdutos += $item['preco'] * $item['quantidade'];
    }

    // Somar frete ao total
    $totalPedido = $totalProdutos + $valorFrete;

    // Inserir pedido com endereço, forma de pagamento e valor total incluindo frete
    $sqlPedido = "
        INSERT INTO pedidos (id_usuario, data_pedido, status, total, forma_pagamento, id_endereco)
        VALUES (:id_usuario, NOW(), 'pago', :total, :forma_pagamento, :id_endereco)
    ";
    $stmtPedido = $db->prepare($sqlPedido);
    $stmtPedido->bindParam(':id_usuario', $id_usuario);
    $stmtPedido->bindParam(':total', $totalPedido);
    $stmtPedido->bindParam(':forma_pagamento', $forma_pagamento);
    $stmtPedido->bindParam(':id_endereco', $id_endereco);
    $stmtPedido->execute();

    $id_pedido = $db->lastInsertId();

    // Inserir itens do pedido
    $sqlItem = "
        INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario)
        VALUES (:id_pedido, :id_produto, :quantidade, :preco_unitario)
    ";
    $stmtItem = $db->prepare($sqlItem);

    foreach ($itens as $item) {
        $stmtItem->execute([
            ':id_pedido' => $id_pedido,
            ':id_produto' => $item['id_produto'],
            ':quantidade' => $item['quantidade'],
            ':preco_unitario' => $item['preco']
        ]);
    }

    // Limpar carrinho
    $sqlClear = "DELETE FROM carrinho WHERE id_usuario = :id_usuario";
    $stmtClear = $db->prepare($sqlClear);
    $stmtClear->bindParam(':id_usuario', $id_usuario);
    $stmtClear->execute();

    // Redirecionar para confirmação
    header("Location: ../views/confirmacao.php?pedido=$id_pedido");
    exit;

} catch (PDOException $e) {
    echo "<h1>Erro no banco de dados:</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    exit;
}
