// ---------------------- MÁSCARAS ----------------------

// CPF
const cpfInput = document.getElementById('cpf');
if (cpfInput) {
    if (!cpfInput.value) cpfInput.value = ''; // garante que seja string
    IMask(cpfInput, {
        mask: '000.000.000-00'
    });
}

// Contato
const contatoInput = document.getElementById('numeroContato');
if (contatoInput) {
    if (!contatoInput.value) contatoInput.value = ''; // garante que seja string
    IMask(contatoInput, {
        mask: '(00) 00000-0000'
    });
}

// ---------------------- CARROSSEL ----------------------
document.querySelectorAll('.carrossel').forEach(carrossel => {
    const slides = carrossel.querySelector('.slides');
    const botaoDireita = carrossel.querySelector('.direita');
    const botaoEsquerda = carrossel.querySelector('.esquerda');

    const slidesVisiveis = 4;
    let indiceAtual = 0;

    function atualizarTransform() {
        const larguraSlide = carrossel.querySelector('.slide').offsetWidth + 4.5; // margem
        const deslocamento = -indiceAtual * larguraSlide;
        slides.style.transform = `translateX(${deslocamento}px)`;
    }

    botaoDireita.addEventListener('click', () => {
        const totalSlides = slides.children.length;
        if (indiceAtual + slidesVisiveis < totalSlides) {
            indiceAtual++;
            atualizarTransform();
        }
    });

    botaoEsquerda.addEventListener('click', () => {
        if (indiceAtual > 0) {
            indiceAtual--;
            atualizarTransform();
        }
    });
});


// ---------------------- BOTÃO CARRINHO ----------------------
document.querySelectorAll('.botao-carrinho').forEach(botao => {
    botao.addEventListener('click', () => {
        alert('Produto adicionado ao carrinho!');
    });
});

// ---------------------- MODAIS ----------------------
function abrirPopupCadastro() {
    const popup = document.getElementById("popupCadastro");
    popup.style.display = "flex";

    // Limpar campos do formulário e máscaras
    const form = document.getElementById('formCadastro');
    if (form) form.reset();
}

function fecharPopupCadastro() {
    document.getElementById("popupCadastro").style.display = "none";
}

// ---------------------- LOGIN ----------------------
function abrirPopupLogin() {
    document.getElementById("popupLogin").style.display = "flex";
}

function fecharPopupLogin() {
    const popup = document.getElementById("popupLogin");
    popup.style.display = "none";

    const form = document.getElementById('formLogin');
    if (form) form.reset();
}

// ---------------------- VARIÁVEIS ----------------------
const formCadastro = document.getElementById('formCadastro');
const formLogin = document.getElementById('formLogin');
const mensagem = document.getElementById('mensagemRetorno');

// ---------------------- CADASTRO ----------------------
if (formCadastro) {
    formCadastro.addEventListener('submit', function (e) {
        e.preventDefault();

        const nome = formCadastro.nome.value.trim();
        const email = formCadastro.email.value.trim();
        const cpf = formCadastro.cpf.value.trim();
        const numeroContato = formCadastro.numeroContato.value.trim(); // corrigido aqui
        const senha = formCadastro.senha.value;

        // Validação do nome (somente letras e espaços)
        const nomeValido = /^[A-Za-zÀ-ÿ\s]+$/.test(nome);
        if (!nomeValido) {
            exibirMensagem('Nome inválido. Use apenas letras e espaços.', 'erro');
            return;
        }

        // Validação da senha (mínimo 6 caracteres)
        if (senha.length < 6) {
            exibirMensagem('Senha deve ter no mínimo 6 caracteres.', 'erro');
            return;
        }

        const formData = new URLSearchParams();
        formData.append('nome', nome);
        formData.append('email', email);
        formData.append('cpf', cpf);
        formData.append('contato', numeroContato);
        formData.append('senha', senha);

        fetch(formCadastro.action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            if (res.status === 'ok') {
                exibirMensagem(res.message || 'Cadastro realizado com sucesso!');
                formCadastro.reset();
            } else {
                exibirMensagem(res.message || 'Erro ao cadastrar.', 'erro');
            }
        })
        .catch(() => exibirMensagem('Erro ao enviar os dados.', 'erro'));
    });
}



// ---------------------- LOGIN ----------------------
if (formLogin) {
    formLogin.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new URLSearchParams();
        formData.append('emailLogin', formLogin.emailLogin.value);
        formData.append('senhaLogin', formLogin.senhaLogin.value);

        fetch(formLogin.action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            if (res.status === 'ok') {
                formLogin.reset();
                setTimeout(() => location.reload(), 1);
            } else {
                exibirMensagem(res.message || 'Erro no login.', 'erro');
            }
        })
        .catch(() => exibirMensagem('Erro ao enviar os dados.', 'erro'));
    });
}

// ---------------------- FUNÇÃO MENSAGEM ----------------------
function exibirMensagem(texto, tipo = 'success') {
    mensagem.innerHTML = texto;
    mensagem.style.display = 'block';
    mensagem.className = tipo === 'success' ? 'mensagem-sucesso' : 'mensagem-erro';
    setTimeout(() => { mensagem.style.display = 'none'; }, 2000);
}

// Fechar mensagem ao clicar nela
if (mensagem) {
    mensagem.addEventListener('click', () => {
        mensagem.style.display = 'none';
    });
}