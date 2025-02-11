<?php
require_once '../config/database.php';
require_once '../models/Evento.php';
require_once '../includes/header.php';

$database = new Database();
$db = $database->getConnection();
$evento = new Evento($db);

$stmt = $evento->listar();
?>

<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Lista de Eventos</h1>
        <a href="cadastrar-evento.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Novo Evento
        </a>
    </div>

    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Casal</th>
                    <th class="py-3 px-6 text-left">Data</th>
                    <th class="py-3 px-6 text-left">Local</th>
                    <th class="py-3 px-6 text-center">Convidados</th>
                    <th class="py-3 px-6 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">
                            <?php echo htmlspecialchars($row['nome_noivo']) . " & " . htmlspecialchars($row['nome_noiva']); ?>
                        </td>
                        <td class="py-3 px-6 text-left">
                            <?php echo date('d/m/Y H:i', strtotime($row['data_evento'])); ?>
                        </td>
                        <td class="py-3 px-6 text-left">
                            <?php echo htmlspecialchars($row['local']); ?>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <?php echo $row['numero_convidados']; ?>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                <a href="gerenciar-convidados.php?evento_id=<?php echo $row['id']; ?>" 
                                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded mr-2">
                                    Convidados
                                </a>
                                <a href="gerar-convites.php?evento_id=<?php echo $row['id']; ?>" 
                                   class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-3 rounded">
                                    Convites
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?> 