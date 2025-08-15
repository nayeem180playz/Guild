<?php
// admin/common/header.php (Final Layout Fix)
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../common/config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - Admin' : 'Admin Panel'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> 
        body { background-color: #111827; color: white; }
        /* This style will ensure the main content is not overlapped by the fixed sidebar on larger screens */
        @media (min-width: 768px) {
            .main-content { margin-left: 16rem; } /* 16rem = width of the sidebar (w-64) */
        }
    </style>
</head>
<body class="bg-gray-900">
    <div class="relative min-h-screen md:flex">
        
        <?php include 'sidebar.php'; // Sidebar is included here ?>

        <div class="flex-1 flex flex-col">
            <header class="bg-gray-800 shadow-md">
                <div class="flex items-center justify-between px-4 py-4">
                    <!-- Hamburger Menu Button (Visible on mobile) -->
                    <button id="admin-sidebar-btn" class="text-white text-2xl md:hidden">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-amber-400">
                        <?php echo isset($page_title) ? $page_title : 'Dashboard'; ?>
                    </h1>
                    <div class="w-8 h-8 md:hidden"></div> <!-- Spacer -->
                </div>
            </header>
            
            <main class="flex-1 p-6 overflow-y-auto">