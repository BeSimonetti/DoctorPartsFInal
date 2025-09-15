<?php
session_start();
include '../classes/usuario.class.php';
include '../classes/carrinho.class.php'; // nova classe
$usuarioLogadoId = $_SESSION['usuario_id'] ?? null;
$usuario = (new Usuario())->selectUsuarioId($usuarioLogadoId);

// Redireciona se o usuário não estiver logado
if (!$usuario) {
    header('Location: index.php');
    exit;
}

$carrinho = new Carrinho();

// Processar ações (atualizar quantidade ou remover item)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['atualizar'])) {
        $carrinho->atualizarQuantidade($_POST['id_carrinho'], $_POST['quantidade']);
    }
    if (isset($_POST['remover'])) {
        $carrinho->removerItem($_POST['id_carrinho']);
    }
    header('Location: carrinho.php');
    exit;
}

$itens = $carrinho->listarItens($usuarioLogadoId);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho de Compras</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/styleCarrinho.css">
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

            <!-- Search bar -->
            <div id="divBusca">
                <input type="text" id="txtBusca" placeholder="Buscar..." />
                <img src="../../../assets/images/lupa.jpg" width="20px" height="20px" id="btnBusca" alt="Search" />
            </div>

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
        </nav>
    </header>
    <main>
        <h1>Meu Carrinho</h1>

        <?php if (empty($itens)): ?>
            <p>Seu carrinho está vazio.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Produto</th>
                        <th>Preço unitário</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($itens as $item): ?>
                        <?php
                        $subtotal = $item['preco'] * $item['quantidade'];
                        $total += $subtotal;
                        ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>" width="50" /></td>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id_carrinho" value="<?= $item['id_carrinho'] ?>">
                                    <input type="number" name="quantidade" value="<?= $item['quantidade'] ?>" min="1" style="width: 50px;">
                                    <button type="submit" name="atualizar">Atualizar</button>
                                </form>
                            </td>
                            <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id_carrinho" value="<?= $item['id_carrinho'] ?>">
                                    <button type="submit" name="remover">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Total</th>
                        <th>R$ <?= number_format($total, 2, ',', '.') ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            <a href="finalizar.php"><button>Finalizar Compra</button></a>
        <?php endif; ?>
    </main>

    <footer id="contato">
        <div class="footer">
            <!-- Contact section -->
            <div class="contato">
                <p><img src="../../../assets/images/whatsapp.png" alt="" id="imagem-contato" />WhatsApp: 54 99269-0769</p>
                <p><img src="../../../assets/images/telefone.png" alt="" id="imagem-contato" />Phone: 54 99262-0769</p>
            </div>

            <!-- Social media -->
            <div class="contato">
                <p><img src="../../../assets/images/intagram.png" alt="" id="imagem-contato" />Instagram: DoctorParts</p>
                <p><img src="../../../assets/images/facebook.png" alt="" id="imagem-contato" />Facebook: DoctorParts</p>
            </div>

            <!-- About section -->
            <div class="footer-descricao">
                <h2>About Us</h2>
                <p>180 years delivering the wrong parts, experts at late delivery with a small variety of brands.</p>
                <p>&copy; 2025 Company Name | All rights reserved</p>
            </div>
        </div>
    </footer>
</body>
</html>
