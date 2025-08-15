<?php
// admin/api_events.php
require_once '../common/config.php';
// Admin session check should be implemented here for security

header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all events
    $result = $conn->query("SELECT * FROM events ORDER BY date DESC");
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    $response['status'] = 'success';
    $response['data'] = $events;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Create and Update
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = trim($_POST['name']);
    $date = trim($_POST['date']);
    $type = trim($_POST['type']);
    $prize = intval($_POST['prize']);

    if (empty($name) || empty($date) || empty($type)) {
        $response['message'] = 'Please fill all required fields.';
    } else {
        if ($id > 0) {
            // Update existing event
            $stmt = $conn->prepare("UPDATE events SET name = ?, date = ?, type = ?, prize = ? WHERE id = ?");
            $stmt->bind_param("sssii", $name, $date, $type, $prize, $id);
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Event updated successfully!';
            } else {
                $response['message'] = 'Failed to update event.';
            }
            $stmt->close();
        } else {
            // Create new event
            $stmt = $conn->prepare("INSERT INTO events (name, date, type, prize) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $name, $date, $type, $prize);
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Event added successfully!';
            } else {
                $response['message'] = 'Failed to add event.';
            }
            $stmt->close();
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle Delete
    parse_str(file_get_contents("php://input"), $data);
    $id = intval($data['id']);
    
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Event deleted successfully!';
        } else {
            $response['message'] = 'Failed to delete event.';
        }
        $stmt->close();
    } else {
        $response['message'] = 'Invalid event ID.';
    }
}

echo json_encode($response);
$conn->close();
?>