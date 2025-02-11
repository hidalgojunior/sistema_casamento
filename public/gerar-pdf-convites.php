<?php
require_once '../config/database.php';
require_once '../models/Convite.php';

$database = new Database();
$db = $database->getConnection();
$convite = new Convite($db);

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : die('ID do evento não especificado');

// Gerar PDF
$pdf = $convite->gerarPDF($evento_id);

if ($pdf) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="convites.pdf"');
    echo $pdf;
} else {
    die('Erro ao gerar PDF');
} 