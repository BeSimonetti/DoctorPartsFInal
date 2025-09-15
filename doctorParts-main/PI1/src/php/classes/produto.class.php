<?php
    include_once 'conexao.class.php';

    class Produto {
        private $id_produto;
        private $id_categoria;
        private $nome;
        private $descricao;
        private $preco;
        private $estoque;
        private $image_url;
        private $data_criacao;

        // ID Produto
        public function getIdProduto() {
            return $this->id_produto;
        }

        public function setIdProduto($id_produto) {
            $this->id_produto = $id_produto;
        }

        // ID Categoria
        public function getIdCategoria() {
            return $this->id_categoria;
        }

        public function setIdCategoria($id_categoria) {
            $this->id_categoria = $id_categoria;
        }

        // Nome
        public function getNome() {
            return $this->nome;
        }

        public function setNome($nome) {
            $this->nome = $nome;
        }

        // Descrição
        public function getDescricao() {
            return $this->descricao;
        }

        public function setDescricao($descricao) {
            $this->descricao = $descricao;
        }

        // Preço
        public function getPreco() {
            return $this->preco;
        }

        public function setPreco($preco) {
            $this->preco = $preco;
        }

        // Estoque
        public function getEstoque() {
            return $this->estoque;
        }

        public function setEstoque($estoque) {
            $this->estoque = $estoque;
        }

        // Image URL
        public function getImageUrl() {
            return $this->image_url;
        }

        public function setImageUrl($image_url) {
            $this->image_url = $image_url;
        }

        // Data de Criação
        public function getDataCriacao() {
            return $this->data_criacao;
        }

        public function setDataCriacao($data_criacao) {
            $this->data_criacao = $data_criacao;
        }

        public function buscarPorNome($termo) {
            $conexao = new Conexao();
            $db = $conexao->getConnection();
            $sql = "SELECT * FROM produtos WHERE nome LIKE :termo";
            try {
                $stmt = $db->prepare($sql);
                $termoBusca = "%" . $termo . "%";
                $stmt->bindParam(':termo', $termoBusca, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo 'Erro ao buscar produto: ' . $e->getMessage();
                return [];
            }
        }
    }

    function listarProdutos($nome) {
    $conexao = new Conexao();
    $db = $conexao->getConnection();
    
    $sql = 'SELECT * FROM produtos WHERE nome LIKE :nome';
    try {
        $stmt = $db->prepare($sql);
        $nome = "%" . $nome . "%"; // permite busca parcial
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // RETORNA ARRAY DE PRODUTOS

    } catch (PDOException $e) {
        echo 'Erro ao listar produto: ' . $e->getMessage();
        return [];
    }

}