<?php
class Convidado {
    private $conn;
    private $table_name = "convidados";

    public $id;
    public $evento_id;
    public $nome;
    public $email;
    public $telefone;
    public $is_padrinho;
    public $confirmado;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar() {
        $query = "INSERT INTO " . $this->table_name . "
                (evento_id, nome, email, telefone, is_padrinho)
                VALUES
                (:evento_id, :nome, :email, :telefone, :is_padrinho)";

        $stmt = $this->conn->prepare($query);

        // Sanitizar dados
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefone = htmlspecialchars(strip_tags($this->telefone));

        // Bind dos valores
        $stmt->bindParam(":evento_id", $this->evento_id);
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefone", $this->telefone);
        $stmt->bindParam(":is_padrinho", $this->is_padrinho);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function listarPorEvento($evento_id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE evento_id = :evento_id 
                 ORDER BY nome";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":evento_id", $evento_id);
        $stmt->execute();
        return $stmt;
    }

    public function listarPadrinhosDisponiveis($evento_id) {
        $query = "SELECT * FROM " . $this->table_name . "
                 WHERE evento_id = :evento_id 
                 AND is_padrinho = true
                 AND id NOT IN (
                     SELECT padrinho1_id FROM padrinhos
                     UNION
                     SELECT padrinho2_id FROM padrinhos
                 )
                 ORDER BY nome";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":evento_id", $evento_id);
        $stmt->execute();
        return $stmt;
    }

    public function buscarPorId($id) {
        $query = "SELECT c.*, e.id as evento_id, conv.codigo_qr 
                  FROM " . $this->table_name . " c
                  LEFT JOIN eventos e ON c.evento_id = e.id
                  LEFT JOIN convites conv ON c.id = conv.convidado_id
                  WHERE c.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar() {
        $query = "UPDATE " . $this->table_name . "
                SET nome = :nome,
                    email = :email,
                    telefone = :telefone,
                    is_padrinho = :is_padrinho
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar dados
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefone = htmlspecialchars(strip_tags($this->telefone));

        // Bind dos valores
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefone", $this->telefone);
        $stmt->bindParam(":is_padrinho", $this->is_padrinho);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function excluir($id) {
        // Primeiro verifica se é padrinho em algum par
        $query_check = "SELECT id FROM padrinhos 
                       WHERE padrinho1_id = :id OR padrinho2_id = :id";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(":id", $id);
        $stmt_check->execute();
        
        if ($stmt_check->rowCount() > 0) {
            return false; // Não pode excluir pois é padrinho
        }

        // Se não for padrinho, pode excluir
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
} 