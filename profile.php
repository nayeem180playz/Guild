<?php
// profile.php
require_once 'common/config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$page_title = "My Profile";
include 'common/header.php';

// Fetch user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, uid, email, achievements FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<div class="p-4 space-y-6">
    <!-- Profile Info Section -->
    <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
        <div class="flex flex-col items-center">
            <img src="https://placehold.co/100x100/000000/FFD700?text=<?php echo substr($user['name'], 0, 1); ?>" alt="Avatar" class="w-24 h-24 rounded-full mb-4 border-4 border-amber-400">
            <h2 class="text-2xl font-bold"><?php echo htmlspecialchars($user['name']); ?></h2>
            <p class="text-gray-400">UID: <?php echo htmlspecialchars($user['uid']); ?></p>
        </div>
    </div>
    
    <!-- Edit Profile Form -->
    <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
        <h3 class="text-lg font-semibold mb-4 text-amber-400">Edit Information</h3>
        <form id="profile-form" class="space-y-4">
            <div>
                <label for="name" class="block mb-2 text-sm font-medium">In-Game Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
            </div>
             <div>
                <label for="email" class="block mb-2 text-sm font-medium">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
            </div>
             <div>
                <label for="achievements" class="block mb-2 text-sm font-medium">Achievements</label>
                <textarea id="achievements" name="achievements" rows="4" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5"><?php echo htmlspecialchars($user['achievements']); ?></textarea>
            </div>
            <button type="submit" class="w-full text-white bg-amber-600 hover:bg-amber-700 font-medium rounded-lg text-sm px-5 py-3">Save Changes</button>
            <div id="profile-feedback" class="text-sm text-center mt-2"></div>
        </form>
    </div>

    <!-- Change Password Form -->
    <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
        <h3 class="text-lg font-semibold mb-4 text-amber-400">Change Password</h3>
        <form id="password-form" class="space-y-4">
            <div>
                <label for="current-password" class="block mb-2 text-sm font-medium">Current Password</label>
                <input type="password" id="current-password" name="current_password" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
            </div>
            <div>
                <label for="new-password" class="block mb-2 text-sm font-medium">New Password</label>
                <input type="password" id="new-password" name="new_password" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
            </div>
            <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-3">Update Password</button>
            <div id="password-feedback" class="text-sm text-center mt-2"></div>
        </form>
    </div>

    <!-- Logout Button -->
    <div class="mt-6">
        <a href="logout.php" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
    </div>
</div>

<script>
// AJAX for Profile Update
document.getElementById('profile-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const feedback = document.getElementById('profile-feedback');
    feedback.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    
    const formData = new FormData(this);
    formData.append('action', 'update_profile');

    const response = await fetch('api_profile.php', { method: 'POST', body: formData });
    const result = await response.json();

    feedback.textContent = result.message;
    feedback.className = result.success ? 'text-green-400' : 'text-red-400';
});

// AJAX for Password Change
document.getElementById('password-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const feedback = document.getElementById('password-feedback');
    feedback.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    
    const formData = new FormData(this);
    formData.append('action', 'change_password');

    const response = await fetch('api_profile.php', { method: 'POST', body: formData });
    const result = await response.json();

    feedback.textContent = result.message;
    feedback.className = result.success ? 'text-green-400' : 'text-red-400';
    if(result.success) this.reset();
});
</script>

<?php include 'common/bottom.php'; ?>