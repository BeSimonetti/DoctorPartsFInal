<?php
include '../classes/endereco.class.php';

function cors() {
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400'); // cache for 1 day
    }
    
    // Access-Control headers são recebidos durante OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
}
cors();

// Resposta padrão
$resposta = [
    'status' => 'error',
    'message' => 'Método não permitido.'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario  = $_POST['id_usuario'] ?? null;
    $id_endereco = $_POST['id_endereco'] ?? null;

    if (!$id_usuario || !$id_endereco) {
        $resposta['message'] = 'Dados incompletos para edição.';
    } else {
        $endereco = new Endereco();

        $endereco->setIdUsuario($id_usuario);
        $endereco->setIdEndereco($id_endereco);
        $endereco->setNumero($_POST['numero'] ?? '');
        $endereco->setCep($_POST['cep'] ?? '');
        $endereco->setRua($_POST['rua'] ?? '');
        $endereco->setBairro($_POST['bairro'] ?? '');
        $endereco->setCidade($_POST['cidade'] ?? '');
        $endereco->setEstado($_POST['estado'] ?? '');
        $endereco->setComplemento($_POST['complemento'] ?? '');

        if ($endereco->editarEndereco()) {
            $resposta['status'] = 'ok';
            $resposta['message'] = '<h1>Endereço atualizado com sucesso!</h1>';
        } else {
            $resposta['message'] = '<p>Erro ao atualizar endereço.</p>';
        }
    }
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode($resposta);
