<?php
// api_profile.php
require_once 'common/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Authentication required.']);
    exit();
}

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Invalid request.'];
$user_id = $_SESSION['user_id'];

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'update_profile') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $achievements = trim($_POST['achievements']);

        if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, achievements = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $email, $achievements, $user_id);
            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Profile updated successfully!'];
            } else {
                $response['message'] = 'Email might already be in use.';
            }
            $stmt->close();
        } else {
            $response['message'] = 'Invalid name or email.';
        }
    } elseif ($_POST['action'] === 'change_password') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        if (strlen($new_password) < 6) {
             $response['message'] = 'New password must be at least 6 characters long.';
        } else {
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            if ($result && password_verify($current_password, $result['password'])) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt_update->bind_param("si", $hashed_password, $user_id);
                if ($stmt_update->execute()) {
                    $response = ['success' => true, 'message' => 'Password changed successfully!'];
                } else {
                    $response['message'] = 'Failed to update password.';
                }
            } else {
                $response['message'] = 'Incorrect current password.';
            }
        }
    }
}

echo json_encode($response);
$conn->close();
?>