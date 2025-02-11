<?php
require_once '../config/database.php';
require_once '../models/Evento.php';
require_once '../includes/header.php';

$database = new Database();
$db = $database->getConnection();
$evento = new Evento($db);

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $evento->nome_noivo = $_POST['nome_noivo'];
    $evento->nome_noiva = $_POST['nome_noiva'];
    $evento->data_evento = $_POST['data_evento'];
    $evento->local = $_POST['local'];
    $evento->numero_convidados = $_POST['numero_convidados'];
    $evento->convite_nominal = isset($_POST['convite_nominal']) ? 1 : 0;

    if ($evento->criar()) {
        $mensagem = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">Evento cadastrado com sucesso!</div>';
    } else {
        $mensagem = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Erro ao cadastrar evento.</div>';
    }
}
?>

<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Cadastrar Novo Evento</h1>
    
    <?php echo $mensagem; ?>

    <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nome_noivo">
                Nome do Noivo
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                   id="nome_noivo" name="nome_noivo" type="text" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nome_noiva">
                Nome da Noiva
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                   id="nome_noiva" name="nome_noiva" type="text" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="data_evento">
                Data e Hora do Evento
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                   id="data_evento" name="data_evento" type="datetime-local" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="local">
                Local
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                   id="local" name="local" type="text" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="numero_convidados">
                Número de Convidados
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                   id="numero_convidados" name="numero_convidados" type="number" required>
        </div>

        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="convite_nominal" class="form-checkbox h-5 w-5 text-blue-600">
                <span class="ml-2 text-gray-700">Convite Nominal</span>
            </label>
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                    type="submit">
                Cadastrar Evento
            </button>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?> 