<?php
// admin/api_admin_stats.php
require_once '../common/config.php';
// Admin session check here
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Failed to update stats.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $match_id = intval($_POST['match_id']);
    $kills = intval($_POST['kills']);
    $damage = intval($_POST['damage']);
    $position = intval($_POST['position']);
    $mvp = isset($_POST['mvp']) ? 1 : 0;

    // Use INSERT ... ON DUPLICATE KEY UPDATE to handle both add and edit
    $stmt = $conn->prepare("
        INSERT INTO stats (user_id, match_id, kills, damage, position, mvp) 
        VALUES (?, ?, ?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE kills=VALUES(kills), damage=VALUES(damage), position=VALUES(position), mvp=VALUES(mvp)
    ");
    $stmt->bind_param("iiiiii", $user_id, $match_id, $kills, $damage, $position, $mvp);

    if ($stmt->execute()) {
        $response = ['success' => true, 'message' => 'Stats updated successfully!'];
    }
}

echo json_encode($response);
$conn->close();
?>
```**এখন `admin/stats.php` ফাইলে JavaScript কোড যোগ করতে হবে:**

১. `Guild/admin/stats.php` ফাইলটি খুলুন।
২. ফাইলের একেবারে শেষে, `<?php include 'common/bottom.php'; ?>`-এর ঠিক **আগে**, নিচের `<script>` অংশটি যোগ করুন।

```javascript
<script>
document.getElementById('stat-form').addEventListener('submit', async function(e){
    e.preventDefault();
    const feedback = document.getElementById('stat-feedback');
    feedback.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    
    const formData = new FormData(this);
    const response = await fetch('api_admin_stats.php', { method: 'POST', body: formData });
    const result = await response.json();
    
    feedback.textContent = result.message;
    feedback.className = result.success ? 'text-green-400 text-center' : 'text-red-400 text-center';

    if(result.success) {
        // Optionally, you can refresh the page to see the new stat in the table
        setTimeout(() => location.reload(), 1500);
    }
});
</script>