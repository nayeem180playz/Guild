<?php
// admin/api_admin_settings.php
require_once '../common/config.php';
// Admin session check here

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Failed to save settings.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction();
    try {
        foreach ($_POST as $key => $value) {
            $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
            $stmt->bind_param("sss", $key, $value, $value);
            $stmt->execute();
        }
        $conn->commit();
        $response = ['success' => true, 'message' => 'Settings saved successfully!'];
    } catch (Exception $e) {
        $conn->rollback();
        $response['message'] = "An error occurred: " . $e->getMessage();
    }
}

echo json_encode($response);
$conn->close();
?>