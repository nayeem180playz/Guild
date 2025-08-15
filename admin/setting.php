<?php
// admin/setting.php
$page_title = "General Settings";
include 'common/header.php';

// Fetch current settings from the database
$settings_result = $conn->query("SELECT * FROM settings");
$settings = [];
while ($row = $settings_result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<div class="p-6">
    <div class="max-w-2xl mx-auto bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-center text-amber-400 mb-6">General & Guild Settings</h2>
        <form id="settings-form" class="space-y-4">
            <!-- App Settings -->
            <h3 class="text-lg font-semibold text-gray-300 border-b border-gray-700 pb-2">App Settings</h3>
            <div>
                <label for="app_name" class="block mb-2 text-sm">App Name</label>
                <input type="text" id="app_name" name="app_name" value="<?php echo htmlspecialchars($settings['app_name'] ?? ''); ?>" class="bg-gray-700 w-full p-2.5 rounded-lg">
            </div>
            <div>
                <label for="app_logo_url" class="block mb-2 text-sm">App Logo URL</label>
                <input type="text" id="app_logo_url" name="app_logo_url" value="<?php echo htmlspecialchars($settings['app_logo_url'] ?? ''); ?>" class="bg-gray-700 w-full p-2.5 rounded-lg">
            </div>
            
            <!-- Guild Settings -->
            <h3 class="text-lg font-semibold text-gray-300 border-b border-gray-700 pb-2 pt-4">Guild Info Settings</h3>
            <div>
                <label for="guild_name" class="block mb-2 text-sm">Guild Name</label>
                <input type="text" id="guild_name" name="guild_name" value="<?php echo htmlspecialchars($settings['guild_name'] ?? ''); ?>" class="bg-gray-700 w-full p-2.5 rounded-lg">
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="guild_level" class="block mb-2 text-sm">Level</label>
                    <input type="text" id="guild_level" name="guild_level" value="<?php echo htmlspecialchars($settings['guild_level'] ?? ''); ?>" class="bg-gray-700 w-full p-2.5 rounded-lg">
                </div>
                <div>
                    <label for="guild_members" class="block mb-2 text-sm">Members</label>
                    <input type="text" id="guild_members" name="guild_members" value="<?php echo htmlspecialchars($settings['guild_members'] ?? ''); ?>" class="bg-gray-700 w-full p-2.5 rounded-lg">
                </div>
                <div>
                    <label for="guild_power" class="block mb-2 text-sm">Power</label>
                    <input type="text" id="guild_power" name="guild_power" value="<?php echo htmlspecialchars($settings['guild_power'] ?? ''); ?>" class="bg-gray-700 w-full p-2.5 rounded-lg">
                </div>
            </div>

            <button type="submit" class="w-full mt-4 text-white bg-amber-600 hover:bg-amber-700 font-medium rounded-lg text-sm px-5 py-3">Save All Settings</button>
            <div id="feedback" class="text-sm text-center mt-2"></div>
        </form>
    </div>
</div>

<script>
document.getElementById('settings-form').addEventListener('submit', async function(e){
    e.preventDefault();
    const feedback = document.getElementById('feedback');
    feedback.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    
    const formData = new FormData(this);
    // You will need an API file to handle this
    const response = await fetch('api_admin_settings.php', { method: 'POST', body: formData });
    const result = await response.json();

    feedback.textContent = result.message;
    feedback.className = result.success ? 'text-green-400' : 'text-red-400';
});
</script>

<?php
// admin/setting.php (General Settings)
$page_title = "General Settings";
include 'common/header.php';

$settings_result = $conn->query("SELECT * FROM settings");
$settings = [];
while ($row = $settings_result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<div class="p-6">
    <div class="max-w-2xl mx-auto bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-center text-amber-400 mb-6">General & Guild Settings</h2>
        <form id="settings-form" class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-300 border-b border-gray-700 pb-2">App Settings</h3>
            <div>
                <label for="app_name" class="block mb-2 text-sm">App Name</label>
                <input type="text" id="app_name" name="app_name" value="<?php echo htmlspecialchars($settings['app_name'] ?? ''); ?>" class="bg-gray-700 w-full p-2.5 rounded-lg">
            </div>
            <div>
                <label for="app_logo_url" class="block mb-2 text-sm">App Logo URL (Link only)</label>
                <input type="url" id="app_logo_url" name="app_logo_url" value="<?php echo htmlspecialchars($settings['app_logo_url'] ?? ''); ?>" class="bg-gray-700 w-full p-2.5 rounded-lg">
            </div>
            
            <h3 class="text-lg font-semibold text-gray-300 border-b border-gray-700 pb-2 pt-4">Guild Info Settings</h3>
            <div>
                <label for="guild_name" class="block mb-2 text-sm">Guild Name</label>
                <input type="text" id="guild_name" name="guild_name" value="<?php echo htmlspecialchars($settings['guild_name'] ?? ''); ?>" class="bg-gray-700 w-full p-2.5 rounded-lg">
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="guild_level" class="block mb-2 text-sm">Level</label>
                    <input type="text" id="guild_level" name="guild_level" value="<?php echo htmlspecialchars($settings['guild_level'] ?? ''); ?>" class="bg-gray-700 w-full p-2.5 rounded-lg">
                </div>
                <div>
                    <label for="guild_members" class="block mb-2 text-sm">Members</label>
                    <input type="text" id="guild_members" name="guild_members" value="<?php echo htmlspecialchars($settings['guild_members'] ?? ''); ?>" class="bg-gray-700 w-full p-2.5 rounded-lg">
                </div>
                <div>
                    <label for="guild_power" class="block mb-2 text-sm">Power</label>
                    <input type="text" id="guild_power" name="guild_power" value="<?php echo htmlspecialchars($settings['guild_power'] ?? ''); ?>" class="bg-gray-700 w-full p-2.5 rounded-lg">
                </div>
            </div>

            <button type="submit" class="w-full mt-4 text-white bg-amber-600 hover:bg-amber-700 font-medium rounded-lg text-sm px-5 py-3">Save All Settings</button>
            <div id="feedback" class="text-sm text-center mt-2"></div>
        </form>
    </div>
</div>

<script>
document.getElementById('settings-form').addEventListener('submit', async function(e){
    e.preventDefault();
    const feedback = document.getElementById('feedback');
    feedback.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    
    const formData = new FormData(this);
    const response = await fetch('api_admin_settings.php', { method: 'POST', body: formData });
    const result = await response.json();

    feedback.textContent = result.message;
    feedback.className = result.success ? 'text-green-400' : 'text-red-400';
});
</script>

<?php include 'common/bottom.php'; ?>```

---

### **৩. `admin/api_admin_settings.php` (Settings API)**

**ফাইল:** `Guild/admin/api_admin_settings.php` (**নতুন ফাইল তৈরি করতে হবে**)

```php
<?php
// admin/api_admin_settings.php
require_once '../common/config.php';
// Admin session check here
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

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
        $response = ['success' => true, 'message' => 'Settings saved successfully! Refresh to see changes.'];
    } catch (Exception $e) {
        $conn->rollback();
        $response['message'] = "An error occurred: " . $e->getMessage();
    }
}

echo json_encode($response);
$conn->close();
?>