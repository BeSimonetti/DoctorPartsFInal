<?php
session_start();
include '../classes/conexao.class.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

$conexao = new Conexao();
$db = $conexao->getConnection();

// Buscar itens do carrinho
$sql = "
    SELECT c.id_produto, c.quantidade, p.preco, p.nome
    FROM carrinho c
    JOIN produtos p ON c.id_produto = p.id_produto
    WHERE c.id_usuario = :id_usuario
";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario);
$stmt->execute();
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($itens) === 0) {
    header("Location: ../views/carrinho.php?msg=vazio");
    exit;
}

// Calcular total dos produtos
$totalProdutos = 0;
foreach ($itens as $item) {
    $totalProdutos += $item['preco'] * $item['quantidade'];
}

// Buscar endereço padrão do usuário
$sqlEndereco = "
    SELECT * FROM enderecos WHERE id_usuario = :id_usuario AND padrao = 1 LIMIT 1
";
$stmtEndereco = $db->prepare($sqlEndereco);
$stmtEndereco->bindParam(':id_usuario', $id_usuario);
$stmtEndereco->execute();
$endereco = $stmtEndereco->fetch(PDO::FETCH_ASSOC);

if (!$endereco) {
    echo "Nenhum endereço padrão cadastrado. <a href='enderecos.php'>Cadastre um endereço</a>";
    exit;
}

// Opções de frete
$fretes = [
    'sedex' => 20.00,
    'pac' => 15.00,
    'retirar' => 0.00
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Finalizar Pedido</title>
    <link rel="stylesheet" href="../../css/styleFinalizar.css">
    <script>
      function atualizarTotal() {
        const freteRadios = document.getElementsByName('frete');
        let valorFrete = 0;
        for (const radio of freteRadios) {
          if (radio.checked) {
            valorFrete = parseFloat(radio.dataset.valor);
            break;
          }
        }
        const totalProdutos = <?= json_encode($totalProdutos) ?>;
        const totalFinal = totalProdutos + valorFrete;
        document.getElementById('total-frete').textContent = 'R$ ' + valorFrete.toFixed(2).replace('.', ',');
        document.getElementById('total-final').textContent = 'R$ ' + totalFinal.toFixed(2).replace('.', ',');
      }
      window.addEventListener('load', () => {
        atualizarTotal();
        const freteRadios = document.getElementsByName('frete');
        for (const radio of freteRadios) {
          radio.addEventListener('change', atualizarTotal);
        }
      });
    </script>
</head>
<body>
    <h1>Finalizar Pedido</h1>

    <h2>Itens no Carrinho</h2>
    <ul>
        <?php foreach ($itens as $item): ?>
            <li><?= htmlspecialchars($item['nome']) ?> - Quantidade: <?= $item['quantidade'] ?> - Preço unitário: R$ <?= number_format($item['preco'], 2, ',', '.') ?></li>
        <?php endforeach; ?>
    </ul>
    <h3>Total dos Produtos: R$ <?= number_format($totalProdutos, 2, ',', '.') ?></h3>

    <h2>Endereço de Entrega</h2>
    <p>
        <?= htmlspecialchars($endereco['rua']) ?>, <?= htmlspecialchars($endereco['numero']) ?><br>
        <?= htmlspecialchars($endereco['bairro']) ?> - <?= htmlspecialchars($endereco['cidade']) ?>/<?= htmlspecialchars($endereco['estado']) ?><br>
        CEP: <?= htmlspecialchars($endereco['cep']) ?><br>
        Complemento: <?= htmlspecialchars($endereco['complemento']) ?>
    </p>

    <h2>Escolha o Frete</h2>
    <form action="../controllers/pagamento.php" method="post" id="form-finalizar">
        <input type="hidden" name="id_endereco" value="<?= $endereco['id_endereco'] ?>" />

        <?php foreach ($fretes as $nome => $valor): ?>
            <label>
                <input 
                  type="radio" 
                  name="frete" 
                  value="<?= $nome ?>" 
                  data-valor="<?= $valor ?>" 
                  required
                  <?= $nome === 'sedex' ? 'checked' : '' ?>
                />
                <?= ucfirst($nome) ?> - R$ <?= number_format($valor, 2, ',', '.') ?>
            </label><br>
        <?php endforeach; ?>

        <h3>Custo do Frete: <span id="total-frete">R$ 0,00</span></h3>
        <h3>Total Final: <span id="total-final">R$ 0,00</span></h3>

        <h2>Escolha a forma de pagamento</h2>

        <label>
            <input type="radio" name="forma_pagamento" value="cartão" required /> Cartão de Crédito
        </label><br>
        <label>
            <input type="radio" name="forma_pagamento" value="boleto" /> Boleto Bancário
        </label><br>
        <label>
            <input type="radio" name="forma_pagamento" value="pix" /> PIX
        </label><br><br>

        <button type="submit">Finalizar Pagamento</button>
    </form>
</body>
</html>
