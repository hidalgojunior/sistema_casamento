<?php
class Evento {
    private $conn;
    private $table_name = "eventos";

    public $id;
    public $nome_noivo;
    public $nome_noiva;
    public $data_evento;
    public $local;
    public $numero_convidados;
    public $convite_nominal;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar() {
        $query = "INSERT INTO " . $this->table_name . "
                (nome_noivo, nome_noiva, data_evento, local, numero_convidados, convite_nominal)
                VALUES
                (:nome_noivo, :nome_noiva, :data_evento, :local, :numero_convidados, :convite_nominal)";

        $stmt = $this->conn->prepare($query);

        // Limpar e sanitizar dados
        $this->nome_noivo = htmlspecialchars(strip_tags($this->nome_noivo));
        $this->nome_noiva = htmlspecialchars(strip_tags($this->nome_noiva));
        $this->local = htmlspecialchars(strip_tags($this->local));

        // Bind dos valores
        $stmt->bindParam(":nome_noivo", $this->nome_noivo);
        $stmt->bindParam(":nome_noiva", $this->nome_noiva);
        $stmt->bindParam(":data_evento", $this->data_evento);
        $stmt->bindParam(":local", $this->local);
        $stmt->bindParam(":numero_convidados", $this->numero_convidados);
        $stmt->bindParam(":convite_nominal", $this->convite_nominal);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function listar() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY data_evento";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 