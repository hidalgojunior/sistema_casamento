<?php
require_once '../config/database.php';
require_once '../models/Evento.php';
require_once '../models/Convidado.php';
require_once '../models/Convite.php';
require_once '../includes/header.php';

$database = new Database();
$db = $database->getConnection();

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : die('ID do evento não especificado');

$evento = new Evento($db);
$convidado = new Convidado($db);
$convite = new Convite($db);

$dados_evento = $evento->buscarPorId($evento_id);
$lista_convidados = $convidado->listarPorEvento($evento_id);
?>

<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Convites do Evento</h1>
        <div class="text-gray-600">
            <?php echo htmlspecialchars($dados_evento['nome_noivo']) . " & " . htmlspecialchars($dados_evento['nome_noiva']); ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="todos-convites">
        <?php while ($convidado = $lista_convidados->fetch(PDO::FETCH_ASSOC)): 
            // Gerar ou recuperar código QR
            if (!isset($convidado['codigo_qr'])) {
                $codigo_qr = $convite->gerar($evento_id, $convidado['id']);
            } else {
                $codigo_qr = $convidado['codigo_qr'];
            }
            
            $qr_code_url = "https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=" . urlencode($codigo_qr);
        ?>
            <div class="bg-white shadow-lg rounded-lg overflow-hidden convite-container">
                <div class="p-6 text-center">
                    <h2 class="text-2xl font-bold mb-4">CONVITE INDIVIDUAL</h2>
                    
                    <div class="text-xl font-bold mb-3">
                        <?php echo htmlspecialchars($dados_evento['nome_noivo']); ?>
                        &
                        <?php echo htmlspecialchars($dados_evento['nome_noiva']); ?>
                    </div>

                    <?php if ($dados_evento['convite_nominal']): ?>
                        <div class="text-lg mb-4">
                            <?php echo htmlspecialchars($convidado['nome']); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-lg mb-4">CONVIDADO(A)</div>
                    <?php endif; ?>

                    <div class="font-bold mb-4">
                        Data: <?php echo date('d/m/Y', strtotime($dados_evento['data_evento'])); ?><br>
                        Horário: <?php echo date('H:i', strtotime($dados_evento['data_evento'])); ?><br>
                        Local: <?php echo htmlspecialchars($dados_evento['local']); ?>
                    </div>

                    <div class="mb-4">
                        <img src="<?php echo $qr_code_url; ?>" alt="QR Code" class="mx-auto">
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="fixed bottom-0 left-0 right-0 bg-white shadow-lg p-4">
        <div class="max-w-6xl mx-auto flex justify-center space-x-4">
            <button onclick="window.print()" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Imprimir Todos os Convites
            </button>
            <a href="gerenciar-convidados.php?evento_id=<?php echo $evento_id; ?>" 
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
    #todos-convites, #todos-convites * {
        visibility: visible;
    }
    .convite-container {
        page-break-inside: avoid;
        break-inside: avoid;
    }
    #todos-convites {
        position: absolute;
        left: 0;
        top: 0;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?> 