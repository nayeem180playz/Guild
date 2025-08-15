<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Login / Sign Up - Guild Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            -webkit-user-select: none; /* Safari */
            -ms-user-select: none; /* IE 10 and IE 11 */
            user-select: none; /* Standard syntax */
        }
    </style>
</head>
<body class="bg-gray-900 text-white font-sans">
    <div class="min-h-screen flex flex-col items-center justify-center p-4" style="background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('https://placehold.co/1080x1920/000000/FFD700?text=Guild+Hub+BG') no-repeat center center/cover;">
        
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-amber-400">Guild Hub</h1>
            <p class="text-gray-300">Your Free Fire Guild Companion</p>
        </div>

        <div class="w-full max-w-md bg-gray-800 bg-opacity-75 rounded-lg shadow-lg p-6 backdrop-blur-sm">
            <div class="flex border-b border-gray-600 mb-6">
                <button id="login-tab-btn" class="flex-1 py-2 text-center font-semibold border-b-2 border-amber-400 text-amber-400">Login</button>
                <button id="signup-tab-btn" class="flex-1 py-2 text-center font-semibold text-gray-400">Sign Up</button>
            </div>

            <!-- Login Form -->
            <form id="login-form" class="space-y-4">
                <div>
                    <label for="login-email" class="block mb-2 text-sm font-medium text-gray-300">Email</label>
                    <input type="email" id="login-email" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" placeholder="your@email.com" required>
                </div>
                <div>
                    <label for="login-password" class="block mb-2 text-sm font-medium text-gray-300">Password</label>
                    <input type="password" id="login-password" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" placeholder="••••••••" required>
                </div>
                <button type="submit" class="w-full text-white bg-amber-600 hover:bg-amber-700 focus:ring-4 focus:outline-none focus:ring-amber-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Login</button>
                <div id="login-feedback" class="text-sm text-center"></div>
            </form>

            <!-- Sign Up Form -->
            <form id="signup-form" class="hidden space-y-4">
                <div>
                    <label for="signup-name" class="block mb-2 text-sm font-medium text-gray-300">In-Game Name</label>
                    <input type="text" id="signup-name" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                </div>
                 <div>
                    <label for="signup-uid" class="block mb-2 text-sm font-medium text-gray-300">Game UID</label>
                    <input type="number" id="signup-uid" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                </div>
                <div>
                    <label for="signup-email" class="block mb-2 text-sm font-medium text-gray-300">Email</label>
                    <input type="email" id="signup-email" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                </div>
                <div>
                    <label for="signup-password" class="block mb-2 text-sm font-medium text-gray-300">Password</label>
                    <input type="password" id="signup-password" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                </div>
                <button type="submit" class="w-full text-white bg-amber-600 hover:bg-amber-700 focus:ring-4 focus:outline-none focus:ring-amber-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Sign Up</button>
                <div id="signup-feedback" class="text-sm text-center"></div>
            </form>
        </div>

    </div>

    <script>
        // Disable context menu, selection, zoom
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.addEventListener('selectstart', event => event.preventDefault());
        document.addEventListener('gesturestart', function (e) {
            e.preventDefault();
        });

        const loginTabBtn = document.getElementById('login-tab-btn');
        const signupTabBtn = document.getElementById('signup-tab-btn');
        const loginForm = document.getElementById('login-form');
        const signupForm = document.getElementById('signup-form');

        loginTabBtn.addEventListener('click', () => {
            loginForm.classList.remove('hidden');
            signupForm.classList.add('hidden');
            loginTabBtn.classList.add('border-amber-400', 'text-amber-400');
            signupTabBtn.classList.remove('border-amber-400', 'text-amber-400');
            signupTabBtn.classList.add('text-gray-400');
        });

        signupTabBtn.addEventListener('click', () => {
            signupForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
            signupTabBtn.classList.add('border-amber-400', 'text-amber-400');
            loginTabBtn.classList.remove('border-amber-400', 'text-amber-400');
            loginTabBtn.classList.add('text-gray-400');
        });

        // AJAX for Login
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            const feedback = document.getElementById('login-feedback');
            
            feedback.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Logging in...`;

            const formData = new FormData();
            formData.append('action', 'login');
            formData.append('email', email);
            formData.append('password', password);

            fetch('api_auth.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    feedback.textContent = 'Login successful! Redirecting...';
                    feedback.className = 'text-green-400';
                    window.location.href = 'index.php';
                } else {
                    feedback.textContent = data.message;
                    feedback.className = 'text-red-400';
                }
            })
            .catch(error => {
                feedback.textContent = 'An error occurred.';
                feedback.className = 'text-red-400';
            });
        });
        
        // AJAX for Signup
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const name = document.getElementById('signup-name').value;
            const uid = document.getElementById('signup-uid').value;
            const email = document.getElementById('signup-email').value;
            const password = document.getElementById('signup-password').value;
            const feedback = document.getElementById('signup-feedback');

            feedback.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Creating account...`;

            const formData = new FormData();
            formData.append('action', 'signup');
            formData.append('name', name);
            formData.append('uid', uid);
            formData.append('email', email);
            formData.append('password', password);

            fetch('api_auth.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    feedback.textContent = 'Account created! Please log in.';
                    feedback.className = 'text-green-400';
                    loginTabBtn.click(); // Switch to login tab
                    loginForm.reset();
                    signupForm.reset();
                } else {
                    feedback.textContent = data.message;
                    feedback.className = 'text-red-400';
                }
            })
            .catch(error => {
                feedback.textContent = 'An error occurred.';
                feedback.className = 'text-red-400';
            });
        });

    </script>
</body>
</html>```

#### **`api_auth.php` (New file in root for AJAX)**

```php
<?php
// api_auth.php
require_once 'common/config.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid Request'];

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'signup') {
        // Sanitize and validate inputs
        $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
        $uid = filter_var(trim($_POST['uid']), FILTER_SANITIZE_NUMBER_INT);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        if (empty($name) || empty($uid) || empty($email) || empty($password)) {
            $response['message'] = 'All fields are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Invalid email format.';
        } else {
            // Check if email or UID already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR uid = ?");
            $stmt->bind_param("ss", $email, $uid);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $response['message'] = 'Email or UID already in use.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt_insert = $conn->prepare("INSERT INTO users (name, uid, email, password) VALUES (?, ?, ?, ?)");
                $stmt_insert->bind_param("ssss", $name, $uid, $email, $hashed_password);
                if ($stmt_insert->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Signup successful!';
                } else {
                    $response['message'] = 'Database error. Please try again.';
                }
                $stmt_insert->close();
            }
            $stmt->close();
        }
    } elseif ($action === 'login') {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $response['message'] = 'Email and password are required.';
        } else {
            $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($user = $result->fetch_assoc()) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $response['success'] = true;
                } else {
                    $response['message'] = 'Incorrect password.';
                }
            } else {
                $response['message'] = 'User not found.';
            }
            $stmt->close();
        }
    }
}

echo json_encode($response);
$conn->close();
?>