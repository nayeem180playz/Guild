<?php
// api_auth.php
require_once 'common/config.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid Request'];

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'signup') {
        $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
        $uid = filter_var(trim($_POST['uid']), FILTER_SANITIZE_NUMBER_INT);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        if (empty($name) || empty($uid) || empty($email) || empty($password)) {
            $response['message'] = 'All fields are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Invalid email format.';
        } else {
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