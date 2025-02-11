<?php
require_once '../config/database.php';
require_once '../models/Convite.php';
require_once '../includes/header.php';

$database = new Database();
$db = $database->getConnection();
$convite = new Convite($db);

$mensagem = '';
$resultado = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codigo_qr'])) {
    $codigo_qr = trim($_POST['codigo_qr']);
    $dados_convite = $convite->buscarPorQR($codigo_qr);
    
    if ($dados_convite) {
        if ($dados_convite['usado']) {
            $mensagem = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                Convite já utilizado em: ' . date('d/m/Y H:i', strtotime($dados_convite['data_uso'])) . '
            </div>';
        } else {
            if ($convite->validar($codigo_qr)) {
                $mensagem = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    Convite válido! Entrada autorizada.
                </div>';
                $resultado = $dados_convite;
            }
        }
    } else {
        $mensagem = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            Convite não encontrado ou inválido.
        </div>';
    }
}
?>

<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Validação de Convites</h1>

    <?php echo $mensagem; ?>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-8">
            <form method="POST" class="mb-6">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="codigo_qr">
                        Código QR
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="codigo_qr" name="codigo_qr" type="text" 
                           placeholder="Digite ou escaneie o código QR"
                           autofocus required>
                </div>

                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                    Validar Convite
                </button>
            </form>

            <?php if ($resultado): ?>
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h2 class="text-xl font-bold mb-4">Informações do Convite</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="font-bold">Evento:</p>
                        <p><?php echo htmlspecialchars($resultado['nome_noivo']) . " & " . htmlspecialchars($resultado['nome_noiva']); ?></p>
                    </div>
                    <div>
                        <p class="font-bold">Convidado:</p>
                        <p><?php echo htmlspecialchars($resultado['convidado_nome']); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Foco automático no campo de código
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('codigo_qr').focus();
});

// Submeter formulário automaticamente ao escanear código QR
document.getElementById('codigo_qr').addEventListener('input', function() {
    if (this.value.length >= 20) { // Comprimento típico do código QR
        this.form.submit();
    }
});
</script>

<?php require_once '../includes/footer.php'; ?> 