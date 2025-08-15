<?php
// admin/common/sidebar.php (Final and Corrected)
?>
<!-- Overlay for mobile -->
<div id="admin-sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30 md:hidden"></div>

<!-- Sidebar -->
<aside id="admin-sidebar" class="bg-gray-800 text-gray-300 w-64 space-y-6 py-7 px-2 fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out z-40 flex flex-col">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-white">Admin Hub</h2>
    </div>
    <nav class="flex-grow">
        <a href="index.php" class="flex items-center py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-tachometer-alt fa-fw mr-3"></i>Dashboard</a>
        <a href="members.php" class="flex items-center py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-users-cog fa-fw mr-3"></i>Members</a>
        <a href="applications.php" class="flex items-center py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-inbox fa-fw mr-3"></i>Applications</a>
        <a href="events.php" class="flex items-center py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-calendar-alt fa-fw mr-3"></i>Events</a>
        <a href="matches.php" class="flex items-center py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-gamepad fa-fw mr-3"></i>Matches</a>
        <a href="stats.php" class="flex items-center py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-chart-line fa-fw mr-3"></i>Stats</a>
        <a href="setting.php" class="flex items-center py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-cogs fa-fw mr-3"></i>Settings</a>
    </nav>
    <div class="p-4">
        <a href="admin_logout.php" class="w-full flex items-center justify-center py-2 bg-red-600 text-white rounded hover:bg-red-700">
            <i class="fas fa-sign-out-alt mr-2"></i>Logout
        </a>
    </div>
</aside>