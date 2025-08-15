<?php
// admin/stats.php (Final Corrected Version)
$page_title = "Manage Player Stats";
include 'common/header.php'; // This includes the header and starts the main content area

// Fetch users and matches for the dropdowns
$users = $conn->query("SELECT id, name FROM users ORDER BY name");
$matches = $conn->query("SELECT id, opponent, time FROM matches ORDER BY time DESC");

// Fetch existing stats to display in the table
$stats_query = "
    SELECT s.id, u.name as user_name, m.opponent, s.kills, s.damage, s.position, s.mvp 
    FROM stats s 
    JOIN users u ON s.user_id = u.id 
    JOIN matches m ON s.match_id = m.id 
    ORDER BY s.id DESC LIMIT 20
";
$stats_data = $conn->query($stats_query);
?>

<!-- This is the main content for the page -->
<div class="p-6">
    <!-- Add/Edit Stats Form -->
    <div class="bg-gray-800 rounded-lg p-6 mb-6 shadow-lg">
        <h3 class="text-lg font-semibold mb-4 text-amber-400">Add or Update Player Stat</h3>
        <form id="stat-form" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Row 1 -->
            <select name="user_id" class="bg-gray-700 p-3 rounded-lg w-full" required>
                <option value="">-- Select Member --</option>
                <?php if ($users && $users->num_rows > 0) {
                    while($user = $users->fetch_assoc()) echo "<option value='{$user['id']}'>".htmlspecialchars($user['name'])."</option>";
                } ?>
            </select>
            <select name="match_id" class="bg-gray-700 p-3 rounded-lg w-full" required>
                <option value="">-- Select Match --</option>
                <?php if ($matches && $matches->num_rows > 0) {
                    while($match = $matches->fetch_assoc()) echo "<option value='{$match['id']}'>vs ".htmlspecialchars($match['opponent'])." on ".date('M d', strtotime($match['time']))."</option>";
                } ?>
            </select>
            <!-- Row 2 -->
            <input type="number" name="kills" placeholder="Kills" class="bg-gray-700 p-3 rounded-lg w-full" required>
            <input type="number" name="damage" placeholder="Damage" class="bg-gray-700 p-3 rounded-lg w-full" required>
            <!-- Row 3 -->
            <input type="number" name="position" placeholder="Position" class="bg-gray-700 p-3 rounded-lg w-full">
            <div class="flex items-center bg-gray-700 p-3 rounded-lg">
                <input type="checkbox" id="mvp" name="mvp" value="1" class="h-5 w-5 mr-3">
                <label for="mvp" class="flex-grow">MVP</label>
            </div>
            <!-- Submit Button -->
            <button type="submit" class="md:col-span-2 bg-amber-600 hover:bg-amber-700 p-3 rounded-lg font-bold">Add / Update Stat</button>
        </form>
        <div id="stat-feedback" class="text-sm text-center mt-3"></div>
    </div>
    
    <!-- Existing Stats Table -->
    <div class="bg-gray-800 rounded-lg overflow-x-auto shadow-lg">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-700 text-xs text-gray-400 uppercase">
                <tr>
                    <th class="p-3">Member</th>
                    <th class="p-3">Match Opponent</th>
                    <th class="p-3 text-center">Kills</th>
                    <th class="p-3 text-center">Damage</th>
                    <th class="p-3 text-center">Position</th>
                    <th class="p-3 text-center">MVP</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
            <?php 
            // THIS IS THE FIX FOR THE FATAL ERROR
            if ($stats_data && $stats_data->num_rows > 0): 
                while($stat = $stats_data->fetch_assoc()): 
            ?>
                <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                    <td class="p-3 font-medium"><?php echo htmlspecialchars($stat['user_name']); ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($stat['opponent']); ?></td>
                    <td class="p-3 text-center font-bold text-amber-400"><?php echo $stat['kills']; ?></td>
                    <td class="p-3 text-center"><?php echo $stat['damage']; ?></td>
                    <td class="p-3 text-center"><?php echo $stat['position'] > 0 ? $stat['position'] : 'N/A'; ?></td>
                    <td class="p-3 text-center"><?php echo $stat['mvp'] ? '<i class="fas fa-check text-green-400"></i>' : '<i class="fas fa-times text-red-400"></i>'; ?></td>
                </tr>
            <?php 
                endwhile; 
            else: 
            ?>
                <tr><td colspan="6" class="text-center p-4 text-gray-500">No stats have been added yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'common/bottom.php'; // This includes the footer and closes the main content area ?>