<?php
// admin/api_applications.php
require_once '../common/config.php';
// Admin session check here

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Invalid request.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status']; // 'Accepted' or 'Rejected'

    if ($id > 0 && ($status === 'Accepted' || $status === 'Rejected')) {
        // Start a transaction
        $conn->begin_transaction();

        try {
            // Step 1: Update the application status
            $stmt_app = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
            $stmt_app->bind_param("si", $status, $id);
            $stmt_app->execute();

            // Step 2: If accepted, add the user to the 'users' table
            if ($status === 'Accepted') {
                // First, get application details
                $stmt_get_app = $conn->prepare("SELECT name, uid FROM applications WHERE id = ?");
                $stmt_get_app->bind_param("i", $id);
                $stmt_get_app->execute();
                $result = $stmt_get_app->get_result();
                $app_data = $result->fetch_assoc();

                if ($app_data) {
                    $name = $app_data['name'];
                    $uid = $app_data['uid'];
                    
                    // A temporary password and email are needed. 
                    // In a real app, you would have a more robust user creation process.
                    $temp_email = $uid . '@guildhub.local'; 
                    $temp_password = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);

                    $stmt_add_user = $conn->prepare("INSERT INTO users (name, uid, email, password, role) VALUES (?, ?, ?, ?, 'Member') ON DUPLICATE KEY UPDATE name=VALUES(name)");
                    $stmt_add_user->bind_param("ssss", $name, $uid, $temp_email, $temp_password);
                    $stmt_add_user->execute();
                } else {
                    throw new Exception("Application data not found.");
                }
            }

            // If everything is fine, commit the transaction
            $conn->commit();
            $response['success'] = true;
            $response['message'] = "Application has been {$status}.";

        } catch (Exception $e) {
            // If something goes wrong, roll back
            $conn->rollback();
            $response['message'] = "An error occurred: " . $e->getMessage();
        }
        
    } else {
        $response['message'] = 'Invalid data provided.';
    }
}

echo json_encode($response);
$conn->close();
?>