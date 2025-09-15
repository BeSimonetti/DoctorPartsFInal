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

header('Content-Type: application/json; charset=UTF-8');

$resposta = [
    'status' => 'error', 
    'message' => 'Método não permitido.'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? null;
    $senhaAtual = $_POST['senha_atual'] ?? null;
    $novaSenha  = $_POST['nova_senha'] ?? null;

    if (!$id_usuario || !$senhaAtual || !$novaSenha) {
        $resposta['message'] = 'Preencha todos os campos.';
    } else {
        $usuario = new Usuario();
        $resultado = $usuario->editarSenha($id_usuario, $senhaAtual, $novaSenha);

        if ($resultado === "Senha alterada com sucesso") {
            $resposta['status'] = 'ok';
            $resposta['message'] = $resultado;
        } else {
            $resposta['message'] = $resultado; // pode ser erro de senha incorreta
        }
    }
}

echo json_encode($resposta);
