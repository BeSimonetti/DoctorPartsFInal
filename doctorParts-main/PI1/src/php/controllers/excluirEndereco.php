<?php
session_start();
include '../classes/endereco.class.php';

header('Content-Type: application/json');

// Log para debug (pode remover em produção)
error_log("POST recebido: " . print_r($_POST, true));

// Verifica se está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'erro', 'message' => 'Usuário não autenticado']);
    exit;
}

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'erro', 'message' => 'Requisição inválida.']);
    exit;
}

// Valida ID
$id_endereco = filter_input(INPUT_POST, 'id_endereco', FILTER_VALIDATE_INT);
if (!$id_endereco) {
    echo json_encode(['status' => 'erro', 'message' => 'ID do endereço inválido.']);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

$endereco = new Endereco();
// Agora o método recebe também o usuário
$linhas = $endereco->deletarEndereco($id_endereco, $id_usuario);

if ($linhas > 0) {
    echo json_encode(['status' => 'ok', 'message' => 'Endereço excluído com sucesso!']);
} else {
    echo json_encode([
        'status' => 'erro',
        'message' => 'Endereço não encontrado, não pertence a este usuário ou não pôde ser excluído.'
    ]);
}
