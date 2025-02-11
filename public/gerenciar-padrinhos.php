<?php
require_once '../config/database.php';
require_once '../models/Evento.php';
require_once '../models/Convidado.php';
require_once '../models/Padrinho.php';
require_once '../includes/header.php';

$database = new Database();
$db = $database->getConnection();

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : die('ID do evento não especificado');

$evento = new Evento($db);
$convidado = new Convidado($db);
$padrinho = new Padrinho($db);

$dados_evento = $evento->buscarPorId($evento_id);
$mensagem = '';

// Processar formulário de novo par de padrinhos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao'])) {
    if ($_POST['acao'] == 'formar_par') {
        $padrinho->evento_id = $evento_id;
        $padrinho->padrinho1_id = $_POST['padrinho1_id'];
        $padrinho->padrinho2_id = $_POST['padrinho2_id'];

        if ($padrinho->criarPar()) {
            $mensagem = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">Par de padrinhos formado com sucesso!</div>';
        } else {
            $mensagem = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Erro ao formar par de padrinhos.</div>';
        }
    }
}

// Buscar padrinhos disponíveis e pares formados
$padrinhos_disponiveis = $convidado->listarPadrinhosDisponiveis($evento_id);
$pares_padrinhos = $padrinho->listarPares($evento_id);
?>

<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gerenciar Pares de Padrinhos</h1>
        <div class="text-gray-600">
            Evento: <?php echo htmlspecialchars($dados_evento['nome_noivo']) . " & " . htmlspecialchars($dados_evento['nome_noiva']); ?>
        </div>
    </div>

    <?php echo $mensagem; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Formulário para Formar Par -->
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-xl font-bold mb-4">Formar Par de Padrinhos</h2>
            
            <form method="POST">
                <input type="hidden" name="acao" value="formar_par">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="padrinho1_id">
                        Primeiro Padrinho/Madrinha
                    </label>
                    <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            id="padrinho1_id" name="padrinho1_id" required>
                        <option value="">Selecione...</option>
                        <?php while ($row = $padrinhos_disponiveis->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['nome']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="padrinho2_id">
                        Segundo Padrinho/Madrinha
                    </label>
                    <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            id="padrinho2_id" name="padrinho2_id" required>
                        <option value="">Selecione...</option>
                        <?php 
                        $padrinhos_disponiveis->execute(); // Reset do cursor
                        while ($row = $padrinhos_disponiveis->fetch(PDO::FETCH_ASSOC)): 
                        ?>
                            <option value="<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['nome']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                        type="submit">
                    Formar Par
                </button>
            </form>
        </div>

        <!-- Lista de Pares -->
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-xl font-bold mb-4">Pares Formados</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Padrinho/Madrinha 1</th>
                            <th class="px-4 py-2 text-left">Padrinho/Madrinha 2</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $pares_padrinhos->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?php echo htmlspecialchars($row['padrinho1_nome']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($row['padrinho2_nome']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?> 