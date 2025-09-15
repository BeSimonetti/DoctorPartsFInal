<?php
    include 'conexao.class.php';

    class Usuario {
        private $id_usuario;
        private $nome;
        private $email;
        private $contato;
        private $senha;
        private $cpf;
        private $data_criacao;
        private $isadmin = false;

        public function getIdUsuario() {
            return $this->id_usuario;
        }
        public function setIdUsuario($id_usuario) {
            $this->id_usuario = $id_usuario;
        }

        public function getNome() {
            return $this->nome;
        }
        public function setNome($nome) {
            $this->nome = $nome;
        }

        public function getEmail() {
            return $this->email;
        }
        public function setEmail($email) {
            $this->email = $email;
        }

        public function getContato() {
            return $this->contato;
        }
        public function setContato($contato) {
            $this->contato = $contato;
        }

        public function getSenha() {
            return $this->senha;
        }
        public function setSenha($senha) {
            $this->senha = $senha;
        }

        public function getCpf() {
            return $this->cpf;
        }
        public function setCpf($cpf) {
            $this->cpf = $cpf;
        }

        public function getDataCriacao() {
            return $this->data_criacao;
        }

        public function setDataCriacao($data_criacao) {
            $this->data_criacao = $data_criacao;
        }

        public function cadastrarUsuario() {
            $conexao = new Conexao();
            $db = $conexao->getConnection();
            
            $sql = 'INSERT INTO usuarios (nome, email, contato, senha, cpf, isadmin) values (:nome, :email, :contato, :senha, :cpf, :isadmin)';
            try{

                $check = $db->query("SELECT COUNT(*) FROM usuarios");
                $totalUsuarios = $check->fetchColumn();

                // Se não houver nenhum usuário, define como admin
                if ($totalUsuarios == 0) {
                    $this->isadmin = true;
                }

                // Gerar o hash da senha antes de armazenar
                $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);

                $stmt = $db->prepare($sql);
                $stmt->bindParam(':nome', $this->nome);
                $stmt->bindParam(':contato', $this->contato);
                $stmt->bindParam(':email', $this->email);
                $stmt->bindParam(':senha', $senhaHash);
                $stmt->bindParam(':cpf', $this->cpf);
                $stmt->bindParam(':isadmin', $this->isadmin, PDO::PARAM_BOOL);
                $stmt->execute();
                return true;
            } catch(PDOException $e){
                echo 'Erro ao inserir usuário: '. $e->getMessage();
                return false;
            }   
        }

        public function listarUsuarios($email, $senha) {
            $conexao = new Conexao();
            $db = $conexao->getConnection();
            
            $sql = 'SELECT * FROM usuarios WHERE email=:email LIMIT 1';
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verifica se o usuário foi encontrado e a senha confere
                if ($usuario && password_verify($senha, $usuario['senha'])) {
                    // Remova o hash antes de retornar os dados, por segurança
                    unset($usuario['senha']);
                    return $usuario;
                } else {
                    // Retorna vazio se senha não confere ou usuário não existe
                    return null;
                }
            } catch (PDOException $e) {
                echo 'Erro ao listar usuários: ' . $e->getMessage();
                return null;
            }
        }

        public function selectUsuarioId($id_usuario){
            $database = new Conexao();
            $db = $database->getConnection();
            $sql = "SELECT * FROM usuarios WHERE id_usuario=:id_usuario";
            try{
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id_usuario',$id_usuario);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
            }catch(PDOException $e){
                echo 'Erro ao listar usuário: ' . $e->getMessage();
                $result = [];
                return $result;
            }
        }

         // === EDITAR DADOS PESSOAIS (sem senha) ===
        public function editarUsuario(){
            $database = new Conexao();
            $db = $database->getConnection();
            $sql = "UPDATE usuarios SET nome=:nome, email=:email, cpf=:cpf, contato=:contato WHERE id_usuario=:id_usuario";
            try{
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id_usuario',$this->id_usuario, PDO::PARAM_INT);
                $stmt->bindParam(':nome',$this->nome);
                $stmt->bindParam(':email',$this->email);
                $stmt->bindParam(':cpf',$this->cpf);
                $stmt->bindParam(':contato',$this->contato);
                $stmt->execute();
                return true;
            } catch(PDOException $e){
                echo 'Erro ao alterar usuário'. $e->getMessage();
                return false;
            }
        }

        public function editarSenha($id_usuario, $senhaAtual, $novaSenha) {
            $conexao = new Conexao();
            $db = $conexao->getConnection();

            try {
                // Buscar a senha atual do usuário
                $sql = "SELECT senha FROM usuarios WHERE id_usuario = :id_usuario";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$usuario) {
                    return "Usuário não encontrado.";
                }

                // Verificar senha atual
                if (!password_verify($senhaAtual, $usuario['senha'])) {
                    return "Senha atual incorreta.";
                }

                // Criar hash da nova senha
                $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

                // Atualizar no banco
                $sqlUpdate = "UPDATE usuarios SET senha = :novaSenha WHERE id_usuario = :id_usuario";
                $stmtUpdate = $db->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':novaSenha', $novaSenhaHash);
                $stmtUpdate->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmtUpdate->execute();

                return "Senha alterada com sucesso";
            } catch (PDOException $e) {
                return "Erro ao alterar senha: " . $e->getMessage();
            }
        }

    }
