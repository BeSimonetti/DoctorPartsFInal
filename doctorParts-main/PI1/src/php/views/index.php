<?php
session_start();
include '../classes/usuario.class.php';
include '../classes/produto.class.php';
include_once '../classes/carrinho.class.php';

$usuarioLogadoId = $_SESSION['usuario_id'] ?? null;

$termoBusca = $_GET['q'] ?? '';
$produtosBuscados = [];

if (!empty($termoBusca)) {
    $produtoObj = new Produto();
    $produtosBuscados = $produtoObj->buscarPorNome($termoBusca);
}

$a = new Usuario();
$usuario = $a->selectUsuarioId($usuarioLogadoId);

$carrinho = new Carrinho();
$quantidadeItens = $usuario ? $carrinho->contarItens($usuarioLogadoId) : 0;


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Carrossel com Descrições</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link to external CSS and JavaScript -->
    <link rel="stylesheet" href="../../css/styleIndex.css">
</head>

<body>
    <!-- Fixed header with navigation menu -->
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
                <form action="index.php" method="GET" style="display: flex; align-items: center;">
                    <input type="text" id="txtBusca" name="q" placeholder="Buscar..." required />
                    <button type="submit" style="background: none; border: none; cursor: pointer;">
                        <img src="../../../assets/images/lupa.jpg" width="20px" height="20px" alt="Buscar" />
                    </button>
                </form>
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

            <!-- Shopping cart icon -->
            <div id="divCarrinho" style="position: relative;">
                <a href="carrinho.php">
                    <img src="../../../assets/images/carrinho.jpg" width="30px" height="30px" alt="Cart" />
                    <?php if ($quantidadeItens > 0): ?>
                        <span class="badge-carrinho"><?= $quantidadeItens ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </nav>
    </header>

    <?php if (!empty($termoBusca)): ?>
    <h2 style="text-align:center;">Resultados para: "<?= htmlspecialchars($termoBusca) ?>"</h2>
    <div class="resultado-lista">
        <?php if (count($produtosBuscados) > 0): ?>
            <?php foreach ($produtosBuscados as $produto): ?>
                <div class="item-busca">
                    <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                    <div class="info">
                        <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                        <p>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                            <input type="hidden" name="quantidade" value="1">
                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;">Nenhum produto encontrado.</p>
        <?php endif; ?>
    </div>
    <?php else: ?>
        <div class="carrosseis-container">  
            <!-- Product carousel section -->
            <div class="carrossel">
                <h1>Diversos</h1>
                <div class="carrossel-wrapper" style="display: flex; align-items: center;">
                    <!-- Left navigation button -->
                    <div class="botoes">
                        <button class="botao-carrosel esquerda" onclick="voltar()">←</button>
                    </div>
                    

                    <!-- Carousel slides container -->
                    <div style="overflow-x: hidden;">
                        <div class="slides" id="slides">
                            <!-- Each product slide -->
                            <?php $produtos = listarProdutos("Bauleto");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Capacete");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Escapamento");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Espelho");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Guidão");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Jaqueta");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right navigation button -->
                    <div class="botoes">
                        <button class="botao-carrosel direita" onclick="avancar()">→</button>
                    </div>
                </div>
            </div>

            <div class="carrossel">
                <h1>Óleos e lubrificantes</h1>
                <div class="carrossel-wrapper" style="display: flex; align-items: center;">
                    <!-- Left navigation button -->
                    <div class="botoes">
                        <button class="botao-carrosel esquerda" onclick="voltar()">←</button>
                    </div>
                    

                    <!-- Carousel slides container -->
                    <div style="overflow-x: hidden;">
                        <div class="slides" id="slides">
                            <!-- Each product slide -->
                            <?php $produtos = listarProdutos("2t510");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("2t800");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("4t300v-5w40");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("4t3000-20w50");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("4t5000-10w30");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("4t7100-15w50");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right navigation button -->
                    <div class="botoes">
                        <button class="botao-carrosel direita" onclick="avancar()">→</button>
                    </div>
                </div>
            </div>

            <div class="carrossel">
                <h1>Pneus</h1>
                <div class="carrossel-wrapper" style="display: flex; align-items: center;">
                    <!-- Left navigation button -->
                    <div class="botoes">
                        <button class="botao-carrosel esquerda" onclick="voltar()">←</button>
                    </div>
                    

                    <!-- Carousel slides container -->
                    <div style="overflow-x: hidden;">
                        <div class="slides" id="slides">
                            <!-- Each product slide -->
                            <?php $produtos = listarProdutos("Power 6");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Power Cup 2");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Supermoto");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Supercorsa SC-V4");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Scorpion Rally STR");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Scorpion Trail-III");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right navigation button -->
                    <div class="botoes">
                        <button class="botao-carrosel direita" onclick="avancar()">→</button>
                    </div>
                </div>
            </div>

            <div class="carrossel">
                <h1>Roupas</h1>
                <div class="carrossel-wrapper" style="display: flex; align-items: center;">
                    <!-- Left navigation button -->
                    <div class="botoes">
                        <button class="botao-carrosel esquerda" onclick="voltar()">←</button>
                    </div>
                    

                    <!-- Carousel slides container -->
                    <div style="overflow-x: hidden;">
                        <div class="slides" id="slides">
                            <!-- Each product slide -->
                            <?php $produtos = listarProdutos("Luva Alpinestars");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Macacão InsaneX");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Luva Qcpj");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Jaqueta Sulaite");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Macacão Volson");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $produtos = listarProdutos("Luva X11");?>
                            <div class="slides" id="slides">
                                <?php foreach ($produtos as $produto): ?>
                                    <div class="slide">
                                        <img src="<?= htmlspecialchars($produto['image_url']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                        <div class="descricao">
                                            <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                        </div>
                                        <form method="POST" action="../controllers/adicionarAoCarrinho.php">
                                            <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
                                            <input type="hidden" name="quantidade" value="1">
                                            <button type="submit" class="botao-carrinho">Adicionar ao carrinho</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right navigation button -->
                    <div class="botoes">
                        <button class="botao-carrosel direita" onclick="avancar()">→</button>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>
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

    <!-- Registration modal PopUp-->
    <div id="popupCadastro" class="modal">
        <div class="modal-conteudo">
            <!-- Close button -->
            <span class="fechar" onclick="fecharPopupCadastro()">&times;</span>
            <h2>Cadastro</h2>
            <!-- Registration form -->
            <form id="formCadastro" action="../controllers/inserirCadastro.php" method="POST">
                <div class="input-modal">   
                    <input type="text" id="nome" name="nome" required placeholder="Insira seu nome">
                </div>
                <div class="input-modal">
                    <input type="email" id="email" name="email" required placeholder="Insira seu e-mail">
                </div>
                <div class="input-modal">
                    <input type="text" id="cpf" name="cpf" required placeholder="Inisira seu CPF">
                </div>
                <div class="input-modal">
                    <input type="tel" id="contato" name="contato" required placeholder="(00) 00000-0000">
                </div>
                <div class="input-modal">
                    <input type="password" id="senha" name="senha" required placeholder="Insira sua senha">
                </div>
                <button type="submit" >Registrar</button>
            </form>
        </div>
    </div>
    <!-- Login PopUp -->
    <div id="popupLogin" class="modal">
        <div class="modal-conteudo">
            <span class="fechar" onclick="fecharPopupLogin()">&times;</span>
            <h2>Login</h2>
            <form id="formLogin" action="../auth/validarLogin.php" method="POST">
                <div class="input-modal">
                    <input type="email" id="emailLogin" name="emailLogin" required placeholder="Insira seu e-mail">
                </div>
                <div class="input-modal">
                    <input type="password" id="senhaLogin" name="senhaLogin" required placeholder="Insira sua senha">
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
    <!-- Successfully message -->
    <div id="mensagemRetorno" class="mensagem-sucesso" ></div>
    <script src="https://unpkg.com/imask"></script>
    <script src="../../js/scriptIndex.js" defer></script>
</body>

</html>