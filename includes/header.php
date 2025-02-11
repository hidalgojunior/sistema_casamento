<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Convites</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="index.php" class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-envelope-open-text text-purple-600"></i>
                            Sistema de Convites
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="index.php" 
                           class="inline-flex items-center px-1 pt-1 text-gray-600 hover:text-gray-800">
                            <i class="fas fa-home mr-1"></i> Início
                        </a>
                        <a href="listar-eventos.php" 
                           class="inline-flex items-center px-1 pt-1 text-gray-600 hover:text-gray-800">
                            <i class="fas fa-calendar-alt mr-1"></i> Eventos
                        </a>
                        <a href="validar-convite.php" 
                           class="inline-flex items-center px-1 pt-1 text-gray-600 hover:text-gray-800">
                            <i class="fas fa-qrcode mr-1"></i> Validar Convite
                        </a>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500">
                        <span class="sr-only">Abrir menu principal</span>
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden md:hidden mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="index.php" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100">
                    <i class="fas fa-home mr-1"></i> Início
                </a>
                <a href="listar-eventos.php" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100">
                    <i class="fas fa-calendar-alt mr-1"></i> Eventos
                </a>
                <a href="validar-convite.php" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100">
                    <i class="fas fa-qrcode mr-1"></i> Validar Convite
                </a>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">

<script>
// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');

    mobileMenuButton.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });
});
</script> 