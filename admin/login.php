<?php
// admin/login.php
// We can start the session here for admin login
session_start();

// If admin is already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Guild Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen">
    <div class="w-full max-w-xs p-6 bg-gray-800 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center text-amber-400 mb-6">Admin Panel</h1>
        <form id="admin-login-form" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
                <input type="text" id="username" name="username" class="mt-1 bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                <input type="password" id="password" name="password" class="mt-1 bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
            </div>
            <button type="submit" class="w-full text-white bg-amber-600 hover:bg-amber-700 focus:ring-4 focus:ring-amber-800 font-medium rounded-lg text-sm px-5 py-2.5">Login</button>
            <div id="feedback" class="text-sm text-center text-red-400 mt-2"></div>
        </form>
    </div>
    <script>
        // Admin Login AJAX
        document.getElementById('admin-login-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const feedback = document.getElementById('feedback');
            feedback.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Logging in...`;

            const formData = new FormData(this);

            // You will need to create api_admin_auth.php for this to work
            const response = await fetch('api_admin_auth.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if (result.success) {
                feedback.className = 'text-green-400';
                feedback.textContent = 'Login successful! Redirecting...';
                window.location.href = 'index.php';
            } else {
                feedback.className = 'text-red-400';
                feedback.textContent = result.message;
            }
        });
    </script>
</body>
</html>```

