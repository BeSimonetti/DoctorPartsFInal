<?php
session_start();
include '../classes/usuario.class.php';
include_once '../classes/conexao.class.php';
include_once '../classes/carrinho.class.php'; 

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Redireciona para a tela principal se não estiver logado
    header('Location: index.php');
    exit;
}

$usuarioLogadoId = $_SESSION['usuario_id'];

$a = new Usuario();
$usuario = $a->selectUsuarioId($usuarioLogadoId);

// Agora buscamos o último pedido do usuário
$conexao = new Conexao();
$db = $conexao->getConnection();

$carrinho = new Carrinho();
$quantidadeItens = $carrinho->contarItens($usuarioLogadoId);

$sqlUltimoPedido = "
    SELECT id_pedido, data_pedido, status
    FROM pedidos
    WHERE id_usuario = :id_usuario
    ORDER BY data_pedido DESC
    LIMIT 1
";
$stmtUltimo = $db->prepare($sqlUltimoPedido);
$stmtUltimo->bindParam(':id_usuario', $usuarioLogadoId, PDO::PARAM_INT);
$stmtUltimo->execute();
$ultimoPedido = $stmtUltimo->fetch(PDO::FETCH_ASSOC);

$itensUltimoPedido = [];
if ($ultimoPedido) {
    // Se existe último pedido, buscar os itens dele
    $sqlItens = "
        SELECT ip.quantidade, ip.preco_unitario, p.nome
        FROM itens_pedido ip
        JOIN produtos p ON ip.id_produto = p.id_produto
        WHERE ip.id_pedido = :id_pedido
    ";
    $stmtItens = $db->prepare($sqlItens);
    $stmtItens->bindParam(':id_pedido', $ultimoPedido['id_pedido'], PDO::PARAM_INT);
    $stmtItens->execute();
    $itensUltimoPedido = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Seus Dados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/styleAreaUsuario.css">
</head>
<body>
    <header id="topo">
        <nav class="menu">
            <!-- Company logo -->
            <div class="logo">
                <a href="index.php"><img src="../../../assets/images/logo.png" alt="Company logo" id="logo" /></a>
            </div>

            <!-- Navigation links -->
            <ul class="nav-links">
                <li><a href="#">Início</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="#contato">Contato</a></li>
            </ul>

            <!-- User login/register -->
            <div class="user-access">
                <?php if ($usuario): ?>
                    <span>Bem-vindo, <a href="areaUsuario.php" class="yolonosay logado"><?= htmlspecialchars($usuario['nome']) ?></a>!</span> | 
                    <a href="../controllers/logout.php" class="yolonosay logado">Sair</a>
                <?php else: ?>
                    <a href="javascript:void(0)" onclick="abrirPopupLogin()" class="yolonosay cadastro">Entre</a> 
                    ou 
                    <a href="javascript:void(0)" onclick="abrirPopupCadastro()" class="yolonosay cadastro">Cadastre-se</a>
                <?php endif; ?>
            </div>

            <!-- Shopping cart icon -->
            <div id="divCarrinho">
                <a href="carrinho.php">
                    <img src="../../../assets/images/carrinho.jpg" width="30px" height="30px" alt="Cart" />
                    <?php if ($quantidadeItens > 0): ?>
                        <span class="badge-carrinho"><?= $quantidadeItens ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="mini-menu">
            <div class="item-mini-menu" onclick="window.location.href='dadosUsuario.php'"> 
                <h1>Meus Dados</h1>
                <p>Altere seus dados cadastrados, endereço ou cadastre <br>um novo endereço.</p>
            </div>
            <div class="item-mini-menu" onclick="window.location.href='meusPedidos.php'">
                <h1>Meus Pedidos</h1>
                <p>Veja o histórico de compras, detalhes dos pedidos e status.</p>
            </div> 
            <div class="ultimo-pedido">
                <h1>Último Pedido</h1>
                <?php if ($ultimoPedido): ?>
                    <p>Pedido Nº: <?= htmlspecialchars($ultimoPedido['id_pedido']) ?></p>
                    <p>Realizado em: <?= date('d/m/Y H:i', strtotime($ultimoPedido['data_pedido'])) ?></p>
                    <p>Status: <?= htmlspecialchars($ultimoPedido['status']) ?></p>

                    <h2>Itens deste Pedido:</h2>
                    <ul>
                        <?php foreach ($itensUltimoPedido as $item): ?>
                            <li>
                                <?= htmlspecialchars($item['nome']) ?> — Quantidade: <?= (int)$item['quantidade'] ?> — Preço unitário: R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <p><a href="confirmacao.php?pedido=<?= htmlspecialchars($ultimoPedido['id_pedido']) ?>">Ver detalhes completos</a></p>
                <?php else: ?>
                    <p>Nenhum pedido realizado.</p>
                <?php endif; ?>
            </div>
        </div> 
    </div>

    <!-- Website footer -->
    <footer id="contato">
        <div class="footer">
            <!-- Contact section -->
            <div class="contato">
                <p><img src="../../../assets/images/whatsapp.png" alt="" id="imagem-contato" />WhatsApp: 54 99269-0769</p>
                <p><img src="../../../assets/images/telefone.png" alt="" id="imagem-contato" />Fone: 54 99262-0769</p>
            </div>

            <!-- Social media -->
            <div class="contato">
                <p><img src="../../../assets/images/intagram.png" alt="" id="imagem-contato" />Instagram: DoctorParts</p>
                <p><img src="../../../assets/images/facebook.png" alt="" id="imagem-contato" />Facebook: DoctorParts</p>
            </div>

            <!-- About section -->
            <div class="footer-descricao">
                <h2>Sobre Nós</h2>
                <p>"180 anos entregando as peças erradas, especialistas em atrasos na entrega e com uma pequena variedade de marcas."</p>
                <p>&copy; 2025 DoctorParts | Todos os direitos reservados</p>
            </div>
        </div>
    </footer>
</body>
</html>
