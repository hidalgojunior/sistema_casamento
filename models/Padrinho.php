<?php
class Padrinho {
    private $conn;
    private $table_name = "padrinhos";

    public $id;
    public $evento_id;
    public $padrinho1_id;
    public $padrinho2_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criarPar() {
        $query = "INSERT INTO " . $this->table_name . "
                (evento_id, padrinho1_id, padrinho2_id)
                VALUES
                (:evento_id, :padrinho1_id, :padrinho2_id)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":evento_id", $this->evento_id);
        $stmt->bindParam(":padrinho1_id", $this->padrinho1_id);
        $stmt->bindParam(":padrinho2_id", $this->padrinho2_id);

        return $stmt->execute();
    }

    public function listarPares($evento_id) {
        $query = "SELECT p.*, 
                 c1.nome as padrinho1_nome,
                 c2.nome as padrinho2_nome
                 FROM " . $this->table_name . " p
                 JOIN convidados c1 ON p.padrinho1_id = c1.id
                 JOIN convidados c2 ON p.padrinho2_id = c2.id
                 WHERE p.evento_id = :evento_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":evento_id", $evento_id);
        $stmt->execute();
        return $stmt;
    }
} 