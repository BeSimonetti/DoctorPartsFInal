<?php
include_once 'conexao.class.php';
    class Endereco {
        private $id_endereco;
        private $id_usuario;
        private $cep;
        private $numero;
        private $rua;
        private $bairro;
        private $cidade;
        private $estado;
        private $complemento;
        private $padrao;

        public function getIdEndereco() {
            return $this->id_endereco;
        }
        public function setIdEndereco($id_endereco) {
            $this->id_endereco = $id_endereco;
        }

        public function getIdUsuario() {
            return $this->id_usuario;
        }
        public function setIdUsuario($id_usuario) {
            $this->id_usuario = $id_usuario;
        }

        public function getCep() {
            return $this->cep;
        }
        public function setCep($cep) {
            $this->cep = $cep;
        }

        public function getNumero() {
            return $this->numero;
        }
        public function setNumero($numero) {
            $this->numero = $numero;
        }

        public function getRua() {
            return $this->rua;
        }
        public function setRua($rua) {
            $this->rua = $rua;
        }

        public function getBairro() {
            return $this->bairro;
        }
        public function setBairro($bairro) {
            $this->bairro = $bairro;
        }

        public function getCidade() {
            return $this->cidade;
        }
        public function setCidade($cidade) {
            $this->cidade = $cidade;
        }

        public function getEstado() {
            return $this->estado;
        }
        public function setEstado($estado) {
            $this->estado = $estado;
        }
       
        public function getComplemento() {
            return $this->complemento;
        }
        public function setComplemento($complemento) {
            $this->complemento = $complemento;
        }

        public function getPadrao() {
            return $this->padrao;
        }
        public function setPadrao($padrao) {
            $this->padrao = $padrao;
        }

        public function inserirEndereco() {
            $conexao = new Conexao();
            $db = $conexao->getConnection();

            // Verifica se o usuário já possui algum endereço
            $sqlCheck = "SELECT COUNT(*) FROM enderecos WHERE id_usuario = :id_usuario";
            $stmtCheck = $db->prepare($sqlCheck);
            $stmtCheck->bindParam(':id_usuario', $this->id_usuario);
            $stmtCheck->execute();
            $existeEndereco = $stmtCheck->fetchColumn();

            $this->padrao = $existeEndereco == 0 ? 1 : 0;

            $sql = 'INSERT INTO enderecos (id_usuario, numero, cep, rua, bairro, cidade, estado, complemento, padrao) 
                    VALUES (:id_usuario, :numero, :cep, :rua, :bairro, :cidade, :estado, :complemento, :padrao)';

            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id_usuario', $this->id_usuario);
                $stmt->bindParam(':numero', $this->numero);
                $stmt->bindParam(':cep', $this->cep);
                $stmt->bindParam(':rua', $this->rua);
                $stmt->bindParam(':bairro', $this->bairro);
                $stmt->bindParam(':cidade', $this->cidade);
                $stmt->bindParam(':estado', $this->estado);
                $stmt->bindParam(':complemento', $this->complemento);
                $stmt->bindParam(':padrao', $this->padrao);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                echo 'Erro ao inserir endereço: ' . $e->getMessage();
                return false;
            }
        }

        public function tornarPadrao($id_usuario, $id_endereco) {
            $conexao = new Conexao();
            $db = $conexao->getConnection();

            try {
                // Desativa o atual
                $sqlReset = "UPDATE enderecos SET padrao = 0 WHERE id_usuario = :id_usuario";
                $stmt = $db->prepare($sqlReset);
                $stmt->bindParam(':id_usuario', $id_usuario);
                $stmt->execute();

                // Ativa o novo
                $sqlSet = "UPDATE enderecos SET padrao = 1 WHERE id_endereco = :id_endereco AND id_usuario = :id_usuario";
                $stmt = $db->prepare($sqlSet);
                $stmt->bindParam(':id_endereco', $id_endereco);
                $stmt->bindParam(':id_usuario', $id_usuario);
                $stmt->execute();

                return true;
            } catch (PDOException $e) {
                echo 'Erro ao definir endereço padrão: ' . $e->getMessage();
                return false;
            }
        }

        public function buscarEnderecosPorUsuario($id_usuario) {
            $database = new Conexao();
            $db = $database->getConnection();
            $sql = "SELECT * FROM enderecos WHERE id_usuario = :id_usuario ORDER BY padrao DESC, id_endereco ASC";
 
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            } catch (PDOException $e) {
                echo 'Erro ao buscar endereços: ' . $e->getMessage();
                return [];
            }
        }
        public function deletarEndereco($id_endereco, $id_usuario) {
            $database = new Conexao();
            $db = $database->getConnection();

            try {
                // Verifica se o endereço é padrão
                $sqlCheck = "SELECT padrao FROM enderecos WHERE id_endereco = :id_endereco AND id_usuario = :id_usuario";
                $stmtCheck = $db->prepare($sqlCheck);
                $stmtCheck->bindParam(':id_endereco', $id_endereco, PDO::PARAM_INT);
                $stmtCheck->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmtCheck->execute();
                $endereco = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if (!$endereco) {
                    return 0; // não existe ou não pertence ao usuário
                }

                // Exclui o endereço
                $sqlDel = "DELETE FROM enderecos WHERE id_endereco = :id_endereco AND id_usuario = :id_usuario";
                $stmtDel = $db->prepare($sqlDel);
                $stmtDel->bindParam(':id_endereco', $id_endereco, PDO::PARAM_INT);
                $stmtDel->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmtDel->execute();

                // Se era padrão, define outro como padrão
                if ($endereco['padrao'] == 1) {
                    $sqlUpdate = "UPDATE enderecos 
                                SET padrao = 1 
                                WHERE id_usuario = :id_usuario 
                                ORDER BY id_endereco ASC 
                                LIMIT 1";
                    $stmtUpdate = $db->prepare($sqlUpdate);
                    $stmtUpdate->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                    $stmtUpdate->execute();
                }

                return $stmtDel->rowCount();

            } catch (PDOException $e) {
                error_log('Erro ao deletar endereço: ' . $e->getMessage());
                return 0;
            }
        }
        public function editarEndereco() {
            $conexao = new Conexao();
            $db = $conexao->getConnection();

            $sql = "UPDATE enderecos 
                    SET cep = :cep,
                        numero = :numero,
                        rua = :rua,
                        bairro = :bairro,
                        cidade = :cidade,
                        estado = :estado,
                        complemento = :complemento
                    WHERE id_endereco = :id_endereco AND id_usuario = :id_usuario";

            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':cep', $this->cep);
                $stmt->bindParam(':numero', $this->numero);
                $stmt->bindParam(':rua', $this->rua);
                $stmt->bindParam(':bairro', $this->bairro);
                $stmt->bindParam(':cidade', $this->cidade);
                $stmt->bindParam(':estado', $this->estado);
                $stmt->bindParam(':complemento', $this->complemento);
                $stmt->bindParam(':id_endereco', $this->id_endereco, PDO::PARAM_INT);
                $stmt->bindParam(':id_usuario', $this->id_usuario, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    return true;
                }
                return false;

            } catch (PDOException $e) {
                echo 'Erro ao editar endereço: ' . $e->getMessage();
                return false;
            }
        }


    }