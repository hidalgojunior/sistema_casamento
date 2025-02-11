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

// Processar formulário de novo convidado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao'])) {
    if ($_POST['acao'] == 'adicionar_convidado') {
        $convidado->evento_id = $evento_id;
        $convidado->nome = $_POST['nome'];
        $convidado->email = $_POST['email'];
        $convidado->telefone = $_POST['telefone'];
        $convidado->is_padrinho = isset($_POST['is_padrinho']) ? 1 : 0;

        if ($convidado->criar()) {
            $mensagem = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">Convidado adicionado com sucesso!</div>';
        } else {
            $mensagem = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">Erro ao adicionar convidado.</div>';
        }
    }
}

// Buscar lista de convidados
$lista_convidados = $convidado->listarPorEvento($evento_id);
?>

<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gerenciar Convidados</h1>
        <div class="text-gray-600">
            Evento: <?php echo htmlspecialchars($dados_evento['nome_noivo']) . " & " . htmlspecialchars($dados_evento['nome_noiva']); ?>
        </div>
    </div>

    <?php echo $mensagem; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Formulário de Novo Convidado -->
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-xl font-bold mb-4">Adicionar Convidado</h2>
            
            <form method="POST">
                <input type="hidden" name="acao" value="adicionar_convidado">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nome">
                        Nome
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="nome" name="nome" type="text" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="email" name="email" type="email">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="telefone">
                        Telefone
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="telefone" name="telefone" type="tel">
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_padrinho" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">É Padrinho/Madrinha</span>
                    </label>
                </div>

                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                        type="submit">
                    Adicionar Convidado
                </button>
            </form>
        </div>

        <!-- Lista de Convidados -->
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-xl font-bold mb-4">Lista de Convidados</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Nome</th>
                            <th class="px-4 py-2 text-center">Padrinho</th>
                            <th class="px-4 py-2 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $lista_convidados->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?php echo htmlspecialchars($row['nome']); ?></td>
                                <td class="px-4 py-2 text-center">
                                    <?php echo $row['is_padrinho'] ? 'Sim' : 'Não'; ?>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a href="gerar-convite.php?convidado_id=<?php echo $row['id']; ?>" 
                                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-sm">
                                        Gerar Convite
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Seção de Padrinhos -->
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 mt-6">
        <h2 class="text-xl font-bold mb-4">Gerenciar Pares de Padrinhos</h2>
        <a href="gerenciar-padrinhos.php?evento_id=<?php echo $evento_id; ?>" 
           class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
            Formar Pares de Padrinhos
        </a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?> 