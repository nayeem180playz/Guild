<?php
// api_apply.php
require_once 'common/config.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Failed to submit application.'];

$name = trim($_POST['name']);
$uid = trim($_POST['uid']);
$age = intval($_POST['age']);
$role_pref = trim($_POST['role_pref']);
$achievements = trim($_POST['achievements']);

if (!empty($name) && !empty($uid) && $age > 0 && !empty($role_pref)) {
    $stmt = $conn->prepare("INSERT INTO applications (name, uid, age, role_pref, achievements) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $name, $uid, $age, $role_pref, $achievements);
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Application submitted successfully! We will review it soon.';
    } else {
        $response['message'] = 'Database error. Please try again.';
    }
    $stmt->close();
} else {
    $response['message'] = 'Please fill all required fields correctly.';
}

echo json_encode($response);
$conn->close();
?>