<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

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

    public function gerarQRCode($codigo) {
        $qrCode = new QrCode($codigo);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        return $result->getDataUri();
    }

    public function gerarPDF($evento_id) {
        require_once __DIR__ . '/../vendor/autoload.php';
        $dompdf = new \Dompdf\Dompdf();
        
        // Buscar dados do evento e convidados
        $query = "SELECT e.*, c.*, conv.codigo_qr 
                 FROM eventos e 
                 JOIN convidados c ON e.id = c.evento_id 
                 LEFT JOIN convites conv ON c.id = conv.convidado_id 
                 WHERE e.id = :evento_id 
                 ORDER BY c.nome";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":evento_id", $evento_id);
        $stmt->execute();
        $convidados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($convidados)) {
            return false;
        }

        $html = '<html><head>
            <style>
                body { font-family: Arial, sans-serif; }
                .convite { 
                    border: 1px solid #ccc;
                    padding: 20px;
                    margin: 10px;
                    text-align: center;
                    page-break-inside: avoid;
                }
                .titulo { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
                .casal { font-size: 16px; font-weight: bold; margin-bottom: 10px; }
                .convidado { font-size: 14px; margin-bottom: 10px; }
                .data { font-size: 14px; margin-bottom: 10px; }
                .qrcode { width: 150px; height: 150px; margin: 10px auto; }
            </style>
        </head><body>';

        foreach ($convidados as $convidado) {
            if (!$convidado['codigo_qr']) {
                $codigo_qr = $this->gerar($evento_id, $convidado['id']);
            } else {
                $codigo_qr = $convidado['codigo_qr'];
            }
            
            $qrcode_image = $this->gerarQRCode($codigo_qr);

            $html .= '<div class="convite">
                <div class="titulo">CONVITE INDIVIDUAL</div>
                <div class="casal">' . htmlspecialchars($convidado['nome_noivo']) . ' & ' . htmlspecialchars($convidado['nome_noiva']) . '</div>';
            
            if ($convidado['convite_nominal']) {
                $html .= '<div class="convidado">' . htmlspecialchars($convidado['nome']) . '</div>';
            } else {
                $html .= '<div class="convidado">CONVIDADO(A)</div>';
            }

            $html .= '<div class="data">
                    Data: ' . date('d/m/Y', strtotime($convidado['data_evento'])) . '<br>
                    Horário: ' . date('H:i', strtotime($convidado['data_evento'])) . '<br>
                    Local: ' . htmlspecialchars($convidado['local']) . '
                </div>
                <img src="' . $qrcode_image . '" class="qrcode">
            </div>';
        }

        $html .= '</body></html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        
        return $dompdf->output();
    }
} 