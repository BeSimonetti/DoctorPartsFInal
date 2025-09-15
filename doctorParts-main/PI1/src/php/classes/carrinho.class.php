<?php
include_once 'conexao.class.php';

class Carrinho {
    private $db;

    public function __construct() {
        $conexao = new Conexao();
        $this->db = $conexao->getConnection();
    }

    public function listarItens($id_usuario) {
        $sql = "
            SELECT c.id_carrinho, c.id_produto, c.quantidade, p.nome, p.preco, p.image_url 
            FROM carrinho c
            JOIN produtos p ON c.id_produto = p.id_produto
            WHERE c.id_usuario = :id_usuario
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarQuantidade($id_carrinho, $novaQuantidade) {
        if ($novaQuantidade < 1) return;
        $sql = "UPDATE carrinho SET quantidade = :qtd WHERE id_carrinho = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':qtd', $novaQuantidade, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id_carrinho, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function removerItem($id_carrinho) {
        $sql = "DELETE FROM carrinho WHERE id_carrinho = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id_carrinho, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function contarItens($id_usuario) {
    $sql = "SELECT SUM(quantidade) as total FROM carrinho WHERE id_usuario = :id_usuario";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado['total'] ?? 0;
    }
}
?>
