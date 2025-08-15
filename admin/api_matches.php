<?php
// admin/api_matches.php
require_once '../common/config.php';
// Admin session check here
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => 'Invalid request.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Create/Update Match
    $match_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $event_id = intval($_POST['event_id']);
    $opponent = trim($_POST['opponent']);
    $time = trim($_POST['time']);
    $map = trim($_POST['map']);
    $mode = trim($_POST['mode']);
    $result = trim($_POST['result']);

    if ($match_id > 0) {
        // Update logic here if needed
    } else {
        // Create new match
        $stmt = $conn->prepare("INSERT INTO matches (event_id, opponent, time, map, mode, result) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $event_id, $opponent, $time, $map, $mode, $result);
        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Match added successfully!'];
        } else {
            $response['message'] = 'Failed to add match.';
        }
    }
} elseif (isset($_GET['event_id'])) {
    // Fetch matches for a specific event
    $event_id = intval($_GET['event_id']);
    $stmt = $conn->prepare("SELECT id, opponent, mode, result FROM matches WHERE event_id = ? ORDER BY time DESC");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $matches = $result->fetch_all(MYSQLI_ASSOC);
    $response = ['status' => 'success', 'data' => $matches];
}

echo json_encode($response);
$conn->close();
?>