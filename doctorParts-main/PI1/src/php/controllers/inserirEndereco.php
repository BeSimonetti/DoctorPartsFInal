<?php
include '../classes/endereco.class.php';

function cors() {
    
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
    
}
cors();
$resposta = [
    'status' => 'error',
    'message' => 'Método não permitido.'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $endereco = new Endereco();

    $endereco->setIdUsuario($_POST['id_usuario']);
    $endereco->setNumero($_POST['numero']);
    $endereco->setCep($_POST['cep']);
    $endereco->setRua($_POST['rua']);
    $endereco->setBairro($_POST['bairro']);
    $endereco->setCidade($_POST['cidade']);
    $endereco->setEstado($_POST['estado']);
    $endereco->setComplemento($_POST['complemento']);

    if ($endereco->inserirEndereco()) {
        $resposta['status'] = 'ok';
        $resposta['message'] = '<h1>Cadastro de endereço efetuado com sucesso!</h1>';
    } else {
        $resposta['message'] = '<p>Erro ao cadastrar endereço.</p>';
    }
}

header('Content-Type: application/json');
echo json_encode($resposta);