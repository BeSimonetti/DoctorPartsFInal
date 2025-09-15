<?php
session_start();
include '../classes/usuario.class.php';
include '../classes/endereco.class.php';
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

$e = new Endereco();
$enderecos = $e->buscarEnderecosPorUsuario($usuarioLogadoId);

$carrinho = new Carrinho();
$quantidadeItens = $carrinho->contarItens($usuarioLogadoId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados do Usuário</title>
    <link rel="stylesheet" href="../../css/styleDadosUsuario.css">
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
    <main>
        <div class="container">
            <div class="blocos">
                <div class="dados-usuario bloco">
                    <h1>Seus Dados</h1>
                    <div class="usuario">
                        <p><strong>Nome:</strong> <?= htmlspecialchars($usuario['nome']) ?></p>    
                        <p><strong>Email do Usuário:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
                        <p><strong>CPF:</strong> <?= htmlspecialchars($usuario['cpf']) ?></p>
                        <p><strong>Telefone:</strong> <?= htmlspecialchars($usuario['contato']) ?></p>
                    </div>
                    <div class="actions">
                        <a href="javascript:void(0)" onclick='abrirPopupEditarDadosUsuario(<?php echo json_encode($usuario, JSON_UNESCAPED_UNICODE); ?>)' class="btn">Editar Dados</a>
                        <a href="javascript:void(0)" onclick='abrirPopupAlterarSenha(<?php echo $usuario["id_usuario"]; ?>)' class="btn">Alterar Senha</a>
                    </div>
                </div>

                <div class="dados-endereco bloco">
                    <h1>Endereços</h1>
                    <?php if (!empty($enderecos)): ?>
                    <div class="actions">
                        <a href="javascript:void(0)" onclick="abrirPopupCadastroEndereco()" class="btn">Cadastrar Novo Endereço</a>
                    </div>
                    <?php foreach ($enderecos as $endereco): ?>
                        <div class="endereco">
                            <div class="card-endereco <?php echo $endereco['padrao'] ? 'padrao' : '' ?>">
                                <p> <strong><?php echo $endereco['padrao'] ? '(Padrão)' : '' ?></strong></p>
                                <p><?php echo htmlspecialchars($endereco['rua']) ?></p>
                                <p>Número: <?php echo htmlspecialchars($endereco['numero']) ?><?php echo $endereco['complemento'] ? ', ' . htmlspecialchars($endereco['complemento']) : '' ?></p>
                                <p>CEP <?php echo htmlspecialchars($endereco['cep']) ?> – <?php echo htmlspecialchars($endereco['cidade']) ?>, <?php echo htmlspecialchars($endereco['estado']) ?></p>


                                <!-- TERMINAR OS BOTÕES E SUAS FUNÇÕES -->
                                <div class="acoes-endereco">
                                    <?php if (count($enderecos) > 1): ?>
                                        <a href="javascript:void(0)" onclick="abrirPopupConfirmacaoExcluirEndereco(<?php echo $endereco['id_endereco'] ?>)" class="link-acao excluirEndereco">EXCLUIR</a>
                                    <?php endif; ?>
                                    <a href="javascript:void(0)" onclick='abrirPopupEditarDadosEndereco(<?php echo json_encode($endereco, JSON_UNESCAPED_UNICODE); ?>)' class="link-acao editar">EDITAR</a>

                                    <?php if (!$endereco['padrao']): ?>
                                       <a href="javascript:void(0)" onclick="tornarPadrao(<?= $endereco['id_endereco'] ?>)" class="link-acao padrao">DEIXAR PADRÃO</a>

                                    <?php else: ?>
                                        <span class="link-acao padrao">PADRÃO</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-address">
                            <p><strong>Nenhum endereço cadastrado.</strong></p>
                        </div>
                        <div class="actions">
                            <a  href="javascript:void(0)" onclick="abrirPopupCadastroEndereco()" class="btn">Cadastrar Endereço</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

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
    <!-- Adress registration modal PopUp-->
    <div id="popupCadastroEndereco" class="modal">
        <div class="modal-conteudo">
            <!-- Close button -->
            <span class="fechar" onclick="fecharPopupCadastroEndereco()">&times;</span>
            <h2>Cadastro de endereço</h2>
            <!-- Registration form -->
            <form id="formCadastroEndereco" action="../controllers/inserirEndereco.php" method="POST">
                <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $usuarioLogadoId ?>">
                <div class="input-modal">
                    <input type="text" id="cep" name="cep" required placeholder="Insira um CEP">
                </div>
                <div class="input-modal">
                    <input type="text" id="numero" name="numero" required placeholder="Número">
                </div>
                <div class="input-modal">
                    <input type="text" id="rua" name="rua" required placeholder="Rua">
                </div>
                <div class="input-modal">
                    <input type="text" id="bairro" name="bairro" required placeholder="Bairro">
                </div>
                <div class="input-modal">
                    <input type="text" id="cidade" name="cidade" required placeholder="Cidade">
                </div>
                <div class="input-modal">
                    <input type="text" id="estado" name="estado" required placeholder="Estado">
                </div>
                <div class="input-modal">
                    <input type="text" id="complemento" name="complemento" placeholder="Complemento (opcional)">
                </div>
                <button type="submit" >Registrar</button>
            </form>
        </div>
    </div>
    <!-- Edit user data modal PopUp-->
    <div id="popupEditarDadosUsuario" class="modal">
        <div class="modal-conteudo">
            <!-- Close button -->
            <span class="fechar" onclick="fecharPopupEditarDadosUsuario()">&times;</span>
            <h2>Editar Dados do Usuário</h2>
            <form id="formEditarDadosUsuario" action="../controllers/editarDadosUsuario.php" method="POST">
                <input type="hidden" name="id_usuario" id="editar_id_usuario">
                <div class="input-modal">   
                    <input type="text" id="editar_nome" name="nome" required placeholder="Insira o seu nome">
                </div>
                <div class="input-modal">
                    <input type="email" id="editar_email" name="email" required placeholder="Insira seu e-mail">
                </div>
                <div class="input-modal">
                    <input type="text" id="editar_cpf" name="cpf" required placeholder="Inisira seu CPF">
                </div>
                <div class="input-modal">
                    <input type="tel" id="editar_contato" name="contato" required placeholder="(00) 00000-0000">
                </div>
                <button type="submit" >Salvar</button>
            </form>
        </div>
    </div>

    <div id="popupAlterarSenha" class="modal">
        <div class="modal-conteudo">
            <!-- Botão fechar -->
            <span class="fechar" onclick="fecharPopupAlterarSenha()">&times;</span>
            <h3>Alterar Senha</h3>
            <form id="formAlterarSenha" action="../controllers/alterarSenha.php" method="POST">
                <input type="hidden" name="id_usuario" id="senha_id_usuario">
                <div class="input-modal">
                    <input type="password" name="senha_atual" id="senha_atual" required placeholder="Senha atual">
                </div>
                <div class="input-modal">
                    <input type="password" name="nova_senha" id="nova_senha" required placeholder="Nova Senha">
                </div>
                <div class="input-modal">
                    <input type="password" id="confirmar_nova_senha" required placeholder="Confirmar Nova Senha">
                </div>
                <input type="checkbox" onclick="mostrarSenhas()"> Mostrar senhas

                <button type="submit">Alterar</button>
            </form>
        </div>
    </div>

    <!-- Edit user data modal PopUp-->
    <div id="popupEditarDadosEndereco" class="modal">
        <div class="modal-conteudo">
            <span class="fechar" onclick="fecharPopupEditarDadosEndereco()">&times;</span>
            <h2>Editar Dados do Endereço</h2>
            <form id="formEditarDadosEndereco" action="../controllers/editarDadosEndereco.php" method="POST">
                <input type="hidden" name="id_endereco" id="editar_id_endereco">
                <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                
                <div class="input-modal">   
                    <input type="text" id="editar_cep" name="cep" required placeholder="Insira um CEP">
                </div>
                <div class="input-modal">
                    <input type="text" id="editar_numero" name="numero" required placeholder="Número">
                </div>
                <div class="input-modal">
                    <input type="text" id="editar_rua" name="rua" required placeholder="Rua">
                </div>
                <div class="input-modal">
                    <input type="text" id="editar_bairro" name="bairro" required placeholder="Bairro">
                </div>
                <div class="input-modal">
                    <input type="text" id="editar_cidade" name="cidade" required placeholder="Cidade">
                </div>
                <div class="input-modal">
                    <input type="text" id="editar_estado" name="estado" required placeholder="Estado">
                </div>
                <div class="input-modal">
                    <input type="text" id="editar_complemento" name="complemento" placeholder="Complemento (opcional)">
                </div>                
                <button type="submit">Salvar</button>
            </form>
        </div>
    </div>



    <div id="popupConfirmExcluirEndereco" class="modal">
        <div class="modal-conteudo">
            <span class="fechar" onclick="fecharPopupConfirmacaoExcluirEndereco()">&times;</span>
            <form id="confirmExcluirEndereco" action="../controllers/excluirEndereco.php" method="POST">
                <input type="hidden" name="id_endereco" id="enderecoIdExcluir">
                <h3>Tem certeza que deseja excluir este endereço?</h2>
                <div class="botoes-confirmacao">
                    <button type="button" onclick="fecharPopupConfirmacaoExcluirEndereco()">Cancelar</button>
                    <button type="submit">Excluir</button>
                </div>
            </form>
        </div>
    </div>

    <div id="mensagemRetorno" class="mensagem-sucesso" ></div>
    <script src="https://unpkg.com/imask"></script>
    <script src="../../js/scriptDadosUsuario.js" defer></script>
</body>
</html>