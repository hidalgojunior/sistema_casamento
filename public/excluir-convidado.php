<?php
require_once '../config/database.php';
require_once '../models/Convidado.php';

$database = new Database();
$db = $database->getConnection();
$convidado = new Convidado($db);

$id = isset($_POST['id']) ? $_POST['id'] : die('ID não especificado');
$evento_id = isset($_POST['evento_id']) ? $_POST['evento_id'] : die('ID do evento não especificado');

$response = ['success' => false, 'message' => ''];

if ($convidado->excluir($id)) {
    $response = ['success' => true, 'message' => 'Convidado excluído com sucesso!'];
} else {
    $response = ['success' => false, 'message' => 'Não foi possível excluir o convidado. Verifique se ele não é padrinho.'];
}

header('Content-Type: application/json');
echo json_encode($response); 