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
    <link rel="stylesheet" href="../../css/styleSobre.css">
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
    <main>
        <!-- Seção com imagem de fundo -->
        <section class="hero">
            <div class="conteudo">
                <h1>
                    Sobre a <span class="doctor">Doctor</span><span class="parts">Parts</span>
                </h1>
                <h2>Quem Somos – DoctorParts</h2>
                <p>
                    Há mais de 50 anos em movimento, a DoctorParts é referência no mercado de duas rodas em Erechim, Rio Grande do Sul, Brasil. 
                    Nascemos com a paixão por motos e a missão de oferecer sempre o melhor em peças e acessórios para todos os estilos de pilotos – do iniciante ao profissional, do street ao off-road. <br><br>
                    
                    Nosso portfólio é completo: peças para motos de pequeno, médio e grande porte, roupas técnicas para Cross e Street, além de uma linha completa de equipamentos e proteções que garantem segurança, desempenho e estilo sobre duas rodas. <br><br>
                    
                    Mais do que uma loja, somos um ponto de encontro para motoqueiros. Um espaço onde tradição e modernidade se misturam: aqui você pode encontrar amigos, conhecer novos estilos de motocicletas, compartilhar experiências e até mesmo fazer uma pausa para um bom café. <br><br>
                    
                    Ao longo dessas cinco décadas, a DoctorParts se mantém em constante evolução, acompanhando as mudanças do mercado, investindo em novas tecnologias e atualizando seus métodos de venda. 
                    Tudo isso para oferecer uma experiência única, que vai muito além da compra de um produto — é sobre viver a cultura das motos em sua essência. <br><br>
                    
                    <strong>DoctorParts – 50 anos acelerando histórias sobre duas rodas.</strong>
                </p>
            </div>
        </section>

    
    </main>
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