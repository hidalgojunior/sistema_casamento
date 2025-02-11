<?php
require_once '../config/database.php';
require_once '../models/Convidado.php';
require_once '../includes/header.php';

$database = new Database();
$db = $database->getConnection();
$convidado = new Convidado($db);

$id = isset($_GET['id']) ? $_GET['id'] : die('ID não especificado');
$dados_convidado = $convidado->buscarPorId($id);

if (!$dados_convidado) {
    die('Convidado não encontrado');
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $convidado->id = $id;
    $convidado->nome = $_POST['nome'];
    $convidado->email = $_POST['email'];
    $convidado->telefone = $_POST['telefone'];
    $convidado->is_padrinho = isset($_POST['is_padrinho']) ? 1 : 0;

    if ($convidado->atualizar()) {
        $mensagem = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            Convidado atualizado com sucesso!
        </div>';
        $dados_convidado = $convidado->buscarPorId($id); // Recarrega os dados
    } else {
        $mensagem = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            Erro ao atualizar convidado.
        </div>';
    }
}
?>

<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Editar Convidado</h1>
        <a href="gerenciar-convidados.php?evento_id=<?php echo $dados_convidado['evento_id']; ?>" 
           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Voltar
        </a>
    </div>

    <?php echo $mensagem; ?>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nome">
                    Nome
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="nome" name="nome" type="text" 
                       value="<?php echo htmlspecialchars($dados_convidado['nome']); ?>" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="email" name="email" type="email" 
                       value="<?php echo htmlspecialchars($dados_convidado['email']); ?>">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="telefone">
                    Telefone
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="telefone" name="telefone" type="tel" 
                       value="<?php echo htmlspecialchars($dados_convidado['telefone']); ?>">
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_padrinho" class="form-checkbox h-5 w-5 text-blue-600"
                           <?php echo $dados_convidado['is_padrinho'] ? 'checked' : ''; ?>>
                    <span class="ml-2 text-gray-700">É Padrinho/Madrinha</span>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                    Atualizar
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?> 