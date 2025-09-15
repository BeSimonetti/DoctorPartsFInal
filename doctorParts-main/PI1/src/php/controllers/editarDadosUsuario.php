<?php
include '../classes/usuario.class.php';

function cors() {
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Max-Age: 86400");
    }

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
    $id_usuario = $_POST['id_usuario'] ?? null;

    if (!$id_usuario) {
        $resposta['message'] = 'Dados incompletos para edição.';
    } else {
        $usuario = new Usuario();
        $usuario->setIdUsuario($id_usuario);
        $usuario->setNome($_POST['nome']);
        $usuario->setEmail($_POST['email']);
        $usuario->setCpf($_POST['cpf']);
        $usuario->setContato($_POST['contato']);

        if ($usuario->editarUsuario()) {
            $resposta['status'] = 'ok';
            $resposta['message'] = 'Dados do usuário atualizados com sucesso!';
        } else {
            $resposta['message'] = 'Erro ao atualizar dados do usuário.';
        }
    }
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode($resposta);
