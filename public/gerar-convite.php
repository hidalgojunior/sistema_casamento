<?php
require_once '../config/database.php';
require_once '../models/Evento.php';
require_once '../models/Convidado.php';
require_once '../models/Convite.php';
require_once '../includes/header.php';

$database = new Database();
$db = $database->getConnection();

$convidado_id = isset($_GET['convidado_id']) ? $_GET['convidado_id'] : die('ID do convidado não especificado');

$convidado = new Convidado($db);
$evento = new Evento($db);
$convite = new Convite($db);

// Buscar dados do convidado e do evento
$dados_convidado = $convidado->buscarPorId($convidado_id);
$dados_evento = $evento->buscarPorId($dados_convidado['evento_id']);

// Gerar código QR se ainda não existir
if (!isset($dados_convidado['codigo_qr'])) {
    $codigo_qr = $convite->gerar($dados_evento['id'], $convidado_id);
} else {
    $codigo_qr = $dados_convidado['codigo_qr'];
}

// Gerar imagem do QR Code
$qr_code_url = $convite->gerarQRCode($codigo_qr);
?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Convite -->
        <div class="p-8 text-center" id="convite">
            <h1 class="text-3xl font-bold mb-6">CONVITE INDIVIDUAL</h1>
            
            <div class="text-2xl font-bold mb-4">
                <?php echo htmlspecialchars($dados_evento['nome_noivo']); ?>
                &
                <?php echo htmlspecialchars($dados_evento['nome_noiva']); ?>
            </div>

            <?php if ($dados_evento['convite_nominal']): ?>
                <div class="text-xl mb-6">
                    <?php echo htmlspecialchars($dados_convidado['nome']); ?>
                </div>
            <?php else: ?>
                <div class="text-xl mb-6">CONVIDADO(A)</div>
            <?php endif; ?>

            <div class="font-bold mb-6">
                Data: <?php echo date('d/m/Y', strtotime($dados_evento['data_evento'])); ?><br>
                Horário: <?php echo date('H:i', strtotime($dados_evento['data_evento'])); ?><br>
                Local: <?php echo htmlspecialchars($dados_evento['local']); ?>
            </div>

            <div class="mb-6">
                <img src="<?php echo $qr_code_url; ?>" alt="QR Code" class="mx-auto">
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-center space-x-4">
            <button onclick="window.print()" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Imprimir Convite
            </button>
            <a href="gerenciar-convidados.php?evento_id=<?php echo $dados_evento['id']; ?>" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #convite, #convite * {
        visibility: visible;
    }
    #convite {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?> 