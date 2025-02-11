<?php
require_once '../config/database.php';
require_once '../models/Evento.php';
require_once '../includes/header.php';

$database = new Database();
$db = $database->getConnection();
$evento = new Evento($db);

$proximos_eventos = $evento->listar()->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Sistema de Gestão de Eventos</h1>
        <p class="text-gray-600 mb-4">Gerencie seus eventos e convites de forma simples e eficiente.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <a href="cadastrar-evento.php" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg p-4 text-center">
                <i class="fas fa-plus-circle text-2xl mb-2"></i>
                <div class="font-bold">Novo Evento</div>
            </a>
            <a href="listar-eventos.php" class="bg-green-500 hover:bg-green-600 text-white rounded-lg p-4 text-center">
                <i class="fas fa-calendar-alt text-2xl mb-2"></i>
                <div class="font-bold">Eventos</div>
            </a>
            <a href="validar-convite.php" class="bg-purple-500 hover:bg-purple-600 text-white rounded-lg p-4 text-center">
                <i class="fas fa-qrcode text-2xl mb-2"></i>
                <div class="font-bold">Validar Convite</div>
            </a>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Próximos Eventos</h2>
        
        <?php if (empty($proximos_eventos)): ?>
            <p class="text-gray-600">Nenhum evento cadastrado.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Casal</th>
                            <th class="px-4 py-2 text-left">Data</th>
                            <th class="px-4 py-2 text-left">Local</th>
                            <th class="px-4 py-2 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proximos_eventos as $evento): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">
                                    <?php echo htmlspecialchars($evento['nome_noivo']) . " & " . htmlspecialchars($evento['nome_noiva']); ?>
                                </td>
                                <td class="px-4 py-2">
                                    <?php echo date('d/m/Y H:i', strtotime($evento['data_evento'])); ?>
                                </td>
                                <td class="px-4 py-2">
                                    <?php echo htmlspecialchars($evento['local']); ?>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a href="gerenciar-convidados.php?evento_id=<?php echo $evento['id']; ?>" 
                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                        Gerenciar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?> 