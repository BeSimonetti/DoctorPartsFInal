<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "erro", "message" => "Usuário não autenticado."]);
    exit;
}

if (!isset($_POST['id_endereco'])) {
    echo json_encode(["status" => "erro", "message" => "ID do endereço não informado."]);
    exit;
}

$id_usuario  = $_SESSION['usuario_id'];
$id_endereco = intval($_POST['id_endereco']);

require_once '../classes/endereco.class.php';

$endereco = new Endereco();
if ($endereco->tornarPadrao($id_usuario, $id_endereco)) {
    echo json_encode(["status" => "ok", "message" => "Endereço definido como padrão com sucesso!"]);
} else {
    echo json_encode(["status" => "erro", "message" => "Não foi possível definir como padrão."]);
}
