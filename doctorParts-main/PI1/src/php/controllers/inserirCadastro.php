<?php
include '../classes/usuario.class.php';

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
    $cadastro = new Usuario();
    //$resposta['message'] = $_POST;
    $cadastro->setNome($_POST['nome']);
    $cadastro->setEmail($_POST['email']);
    $cadastro->setCpf($_POST['cpf']);
    $cadastro->setContato($_POST['contato']);
    $cadastro->setSenha($_POST['senha']);


    if ($cadastro->cadastrarUsuario()) {
        $resposta['status'] = 'ok';
        $resposta['message'] = "<h1>Cadastro efetuado com sucesso!</h1>";
    } else {
        $resposta['message'] =  "<p>Erro ao cadastrar usuário.</xp>";
    }
}

header('Content-Type: application/json');
echo json_encode($resposta);