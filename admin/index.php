<?php
// admin/index.php

// Use the admin header
$page_title = "Admin Dashboard";
include 'common/header.php'; 

// Fetch stats
$total_members = $conn->query("SELECT COUNT(id) as count FROM users")->fetch_assoc()['count'];
$upcoming_matches = $conn->query("SELECT COUNT(id) as count FROM matches WHERE time > NOW()")->fetch_assoc()['count'];
$total_wins = $conn->query("SELECT COUNT(id) as count FROM matches WHERE result = 'Win'")->fetch_assoc()['count'];
$pending_apps = $conn->query("SELECT COUNT(id) as count FROM applications WHERE status = 'Pending'")->fetch_assoc()['count'];
?>

<div class="p-6 space-y-8">
    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Members Card -->
        <div class="bg-gray-800 p-5 rounded-lg shadow-lg flex items-center space-x-4">
            <div class="bg-blue-500 p-3 rounded-full">
                <i class="fas fa-users text-2xl text-white"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Total Members</p>
                <p class="text-2xl font-bold"><?php echo $total_members; ?></p>
            </div>
        </div>
        <!-- Upcoming Matches Card -->
        <div class="bg-gray-800 p-5 rounded-lg shadow-lg flex items-center space-x-4">
            <div class="bg-green-500 p-3 rounded-full">
                <i class="fas fa-gamepad text-2xl text-white"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Upcoming Matches</p>
                <p class="text-2xl font-bold"><?php echo $upcoming_matches; ?></p>
            </div>
        </div>
        <!-- Total Wins Card -->
        <div class="bg-gray-800 p-5 rounded-lg shadow-lg flex items-center space-x-4">
            <div class="bg-yellow-500 p-3 rounded-full">
                <i class="fas fa-trophy text-2xl text-white"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Total Wins</p>
                <p class="text-2xl font-bold"><?php echo $total_wins; ?></p>
            </div>
        </div>
        <!-- Pending Applications Card -->
        <div class="bg-gray-800 p-5 rounded-lg shadow-lg flex items-center space-x-4">
            <div class="bg-red-500 p-3 rounded-full">
                <i class="fas fa-inbox text-2xl text-white"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Pending Applications</p>
                <p class="text-2xl font-bold"><?php echo $pending_apps; ?></p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div>
        <h3 class="text-xl font-semibold mb-4 text-gray-200">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="events.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-5 rounded-lg flex flex-col items-center justify-center text-center">
                <i class="fas fa-calendar-plus text-2xl mb-2"></i> <span>Manage Events</span>
            </a>
            <a href="matches.php" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-4 px-5 rounded-lg flex flex-col items-center justify-center text-center">
                <i class="fas fa-plus-circle text-2xl mb-2"></i> <span>Add Match</span>
            </a>
             <a href="members.php" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-4 px-5 rounded-lg flex flex-col items-center justify-center text-center">
                <i class="fas fa-users-cog text-2xl mb-2"></i> <span>Manage Members</span>
            </a>
            <a href="applications.php" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 px-5 rounded-lg flex flex-col items-center justify-center text-center">
                <i class="fas fa-file-alt text-2xl mb-2"></i> <span>View Applications</span>
            </a>
        </div>
    </div>
</div>

<?php 
// Use the admin bottom
include 'common/bottom.php'; 
?>