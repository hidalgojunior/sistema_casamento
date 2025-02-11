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
} 