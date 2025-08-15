<?php
// index.php (Upgraded with more content)
require_once 'common/config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$settings_result = $conn->query("SELECT * FROM settings");
$settings = [];
while ($row = $settings_result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Fetch upcoming events
$events = $conn->query("SELECT id, name, date, type FROM events WHERE date >= NOW() ORDER BY date ASC LIMIT 5");
// Fetch latest match results
$latest_matches = $conn->query("SELECT opponent, map, mode, result FROM matches WHERE result IS NOT NULL ORDER BY time DESC LIMIT 3");

$page_title = $settings['app_name'] ?? "Dashboard";
include 'common/header.php';
?>

<div class="p-4 space-y-6">
    <!-- Guild Info Section -->
    <div class="bg-gray-800 rounded-lg p-4 text-center shadow-lg">
        <h2 class="text-2xl font-bold text-amber-400"><?php echo htmlspecialchars($settings['guild_name'] ?? 'Guild Name'); ?></h2>
        <div class="flex justify-around mt-4 text-gray-300">
            <div><p class="font-bold text-xl"><?php echo htmlspecialchars($settings['guild_level'] ?? 'N/A'); ?></p><p class="text-xs">Level</p></div>
            <div><p class="font-bold text-xl"><?php echo htmlspecialchars($settings['guild_members'] ?? 'N/A'); ?></p><p class="text-xs">Members</p></div>
            <div><p class="font-bold text-xl"><?php echo htmlspecialchars($settings['guild_power'] ?? 'N/A'); ?></p><p class="text-xs">Power</p></div>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div>
        <h3 class="text-lg font-semibold mb-2 text-gray-200">Upcoming Events</h3>
        <div class="flex overflow-x-auto space-x-4 pb-4">
            <?php if ($events && $events->num_rows > 0): while($event = $events->fetch_assoc()): ?>
            <div class="flex-shrink-0 w-64 bg-gray-800 rounded-lg p-4">
                <p class="font-bold text-amber-500 truncate"><?php echo htmlspecialchars($event['name']); ?></p>
                <p class="text-sm text-gray-400"><i class="fas fa-calendar-alt mr-1"></i> <?php echo date('M d, Y', strtotime($event['date'])); ?></p>
                <p class="text-sm text-gray-400"><i class="fas fa-trophy mr-1"></i> <?php echo htmlspecialchars($event['type']); ?></p>
                <a href="match_schedule.php?event_id=<?php echo $event['id']; ?>" class="mt-3 block w-full text-center bg-amber-600 text-white py-1 rounded-md text-sm">View</a>
            </div>
            <?php endwhile; else: ?>
            <p class="text-gray-500">No upcoming events.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Latest Match Results -->
    <div>
        <h3 class="text-lg font-semibold mb-2 text-gray-200">Latest Results</h3>
        <div class="space-y-3">
        <?php if ($latest_matches && $latest_matches->num_rows > 0): while($match = $latest_matches->fetch_assoc()): ?>
            <div class="bg-gray-800 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-bold">vs. <?php echo htmlspecialchars($match['opponent']); ?></p>
                        <p class="text-xs text-gray-400"><?php echo htmlspecialchars($match['map']); ?> | <?php echo htmlspecialchars($match['mode']); ?></p>
                    </div>
                    <?php 
                        $result_color = $match['result'] === 'Win' ? 'text-green-400' : ($match['result'] === 'Loss' ? 'text-red-400' : 'text-gray-400');
                    ?>
                    <div class="text-center"><p class="text-2xl font-bold <?php echo $result_color; ?>"><?php echo strtoupper($match['result']); ?></p></div>
                </div>
            </div>
        <?php endwhile; else: ?>
            <p class="text-gray-500">No recent match results.</p>
        <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'common/bottom.php'; ?>