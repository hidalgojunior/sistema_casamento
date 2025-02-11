<?php
class Convite {
    private $conn;
    private $table_name = "convites";

    public $id;
    public $evento_id;
    public $convidado_id;
    public $codigo_qr;
    public $usado;
    public $data_uso;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function gerar($evento_id, $convidado_id) {
        // Gera um código QR único
        $codigo_qr = uniqid('CONV_') . bin2hex(random_bytes(8));

        $query = "INSERT INTO " . $this->table_name . "
                (evento_id, convidado_id, codigo_qr)
                VALUES
                (:evento_id, :convidado_id, :codigo_qr)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":evento_id", $evento_id);
        $stmt->bindParam(":convidado_id", $convidado_id);
        $stmt->bindParam(":codigo_qr", $codigo_qr);

        if($stmt->execute()) {
            return $codigo_qr;
        }
        return false;
    }

    public function validar($codigo_qr) {
        $query = "UPDATE " . $this->table_name . "
                 SET usado = true, data_uso = NOW()
                 WHERE codigo_qr = :codigo_qr AND usado = false";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":codigo_qr", $codigo_qr);
        return $stmt->execute();
    }

    public function buscarPorQR($codigo_qr) {
        $query = "SELECT c.*, conv.nome as convidado_nome, e.nome_noivo, e.nome_noiva
                 FROM " . $this->table_name . " c
                 JOIN convidados conv ON c.convidado_id = conv.id
                 JOIN eventos e ON c.evento_id = e.id
                 WHERE c.codigo_qr = :codigo_qr";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":codigo_qr", $codigo_qr);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 