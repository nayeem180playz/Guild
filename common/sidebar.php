<?php
// common/sidebar.php
?>
<!-- Sidebar Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden"></div>

<!-- Sidebar Menu -->
<aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-gray-800 text-white transform -translate-x-full transition-transform duration-300 ease-in-out z-[60]">
    <div class="p-4 border-b border-gray-700">
        <h2 class="text-2xl font-bold text-amber-400">Guild Hub</h2>
        <p class="text-sm text-gray-400">Navigation Menu</p>
    </div>
    <nav class="mt-4">
        <a href="index.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700">Dashboard</a>
        <a href="events.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700">Events</a>
        <a href="members.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700">Members</a>
        <a href="stats.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700">Guild Stats</a>
        <a href="apply.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700">Apply to Guild</a>
        <a href="profile.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700">My Profile</a>
        <a href="logout.php" class="absolute bottom-4 left-4 right-4 flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">Logout</a>
    </nav>
</aside>