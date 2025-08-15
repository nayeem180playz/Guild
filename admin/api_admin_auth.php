<?php
// admin/api_admin_auth.php
require_once '../common/config.php'; // Go one directory up to find the common folder

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Invalid Request'];

if (isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $response['message'] = 'Username and password are required.';
    } else {
        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($admin = $result->fetch_assoc()) {
            // Verify the password
            if (password_verify($password, $admin['password'])) {
                // Password is correct, start the session
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $username;
                $response['success'] = true;
                $response['message'] = 'Login successful!';
            } else {
                // Incorrect password
                $response['message'] = 'Incorrect password.';
            }
        } else {
            // Admin user not found
            $response['message'] = 'Admin user not found.';
        }
        $stmt->close();
    }
}

echo json_encode($response);
$conn->close();
?>