<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Menu Test Page</title>
    <!-- External Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #111827; color: white; }
    </style>
</head>
<body class="pb-16">

    <!-- =========== HEADER HTML =========== -->
    <header class="bg-gray-800 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="https://placehold.co/40x40/000000/FFD700?text=GH" alt="Guild Logo" class="h-8 w-8 rounded-full">
                <h1 class="text-xl font-bold text-amber-400">Test Page</h1>
            </div>
            <!-- The Menu Button -->
            <button id="sidebar-btn" class="text-xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- =========== SIDEBAR HTML =========== -->
    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden"></div>
    <!-- Sidebar Menu -->
    <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-gray-800 text-white transform -translate-x-full transition-transform duration-300 ease-in-out z-[60]">
        <div class="p-4 border-b border-gray-700">
            <h2 class="text-2xl font-bold text-amber-400">Test Menu</h2>
        </div>
        <nav class="mt-4">
            <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700">Link 1</a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700">Link 2</a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700">Link 3</a>
        </nav>
    </aside>

    <!-- Page Content -->
    <main class="container mx-auto p-4">
        <h1 class="text-2xl">This is a test page.</h1>
        <p class="mt-2">Click the menu button (≡) in the top-right corner.</p>
    </main>


    <!-- =========== JAVASCRIPT LOGIC =========== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("Page loaded and script is running!");

            const sidebarBtn = document.getElementById('sidebar-btn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            console.log("Sidebar Button:", sidebarBtn); // Should show the button element
            console.log("Sidebar Menu:", sidebar);     // Should show the aside element
            console.log("Sidebar Overlay:", sidebarOverlay); // Should show the div element

            if (sidebarBtn && sidebar && sidebarOverlay) {
                console.log("All elements found. Adding click listeners.");
                sidebarBtn.addEventListener('click', () => {
                    console.log("Menu button clicked!");
                    sidebar.classList.toggle('-translate-x-full');
                    sidebarOverlay.classList.toggle('hidden');
                });

                sidebarOverlay.addEventListener('click', () => {
                    console.log("Overlay clicked!");
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                });
            } else {
                console.error("One or more sidebar elements were not found!");
            }
        });
    </script>
</body>
</html>