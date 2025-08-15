<?php
// common/header.php (Corrected - No more database calls here)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Guild Hub'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #111827; color: white; -webkit-user-select: none; user-select: none; }
    </style>
</head>
<body class="bg-gray-900 text-white pb-16">
    <header class="bg-gray-800 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="<?php echo htmlspecialchars($settings['app_logo_url'] ?? 'https://placehold.co/40x40/000/FFF?text=G'); ?>" alt="Logo" class="h-8 w-8 rounded-full">
                <h1 class="text-xl font-bold text-amber-400"><?php echo htmlspecialchars($settings['app_name'] ?? 'Guild Hub'); ?></h1>
            </div>
            <button id="sidebar-btn" class="text-xl"><i class="fas fa-bars"></i></button>
        </div>
    </header>
    <?php include 'sidebar.php'; ?>
    <main class="container mx-auto">