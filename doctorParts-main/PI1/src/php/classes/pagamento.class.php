<?php
include_once 'conexao.class.php';

class Pagamento {
    private $id_pagamento;
    private $id_usuario;
    private $id_pedido;
    private $forma_pagamento; // ex: 'cartao', 'boleto', 'pix'
    private $status; // ex: 'pendente', 'confirmado', 'cancelado'
    private $valor;
    private $data_pagamento;

    public function getIdPagamento() {
        return $this->id_pagamento;
    }
    public function setIdPagamento($id) {
        $this->id_pagamento = $id;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }
    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function getIdPedido() {
        return $this->id_pedido;
    }
    public function setIdPedido($id_pedido) {
        $this->id_pedido = $id_pedido;
    }

    public function getFormaPagamento() {
        return $this->forma_pagamento;
    }
    public function setFormaPagamento($forma) {
        $this->forma_pagamento = $forma;
    }

    public function getStatus() {
        return $this->status;
    }
    public function setStatus($status) {
        $this->status = $status;
    }

    public function getValor() {
        return $this->valor;
    }
    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getDataPagamento() {
        return $this->data_pagamento;
    }
    public function setDataPagamento($data) {
        $this->data_pagamento = $data;
    }

    public function inserirPagamento() {
        $conexao = new Conexao();
        $db = $conexao->getConnection();

        $sql = "INSERT INTO pagamentos (id_usuario, id_pedido, forma_pagamento, status, valor, data_pagamento)
                VALUES (:id_usuario, :id_pedido, :forma_pagamento, :status, :valor, :data_pagamento)";

        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id_usuario', $this->id_usuario);
            $stmt->bindParam(':id_pedido', $this->id_pedido);
            $stmt->bindParam(':forma_pagamento', $this->forma_pagamento);
            $stmt->bindParam(':status', $this->status);
            $stmt->bindParam(':valor', $this->valor);
            $stmt->bindParam(':data_pagamento', $this->data_pagamento);
            $stmt->execute();

            $this->id_pagamento = $db->lastInsertId();

            return true;
        } catch (PDOException $e) {
            echo "Erro ao inserir pagamento: " . $e->getMessage();
            return false;
        }
    }

    // Você pode criar métodos para atualizar status, consultar pagamentos etc.
}
?>
