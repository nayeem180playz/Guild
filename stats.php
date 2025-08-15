<?php
// stats.php
require_once 'common/config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$page_title = "Guild Stats";
include 'common/header.php';

// Fetch Guild Stats
$total_matches = $conn->query("SELECT COUNT(id) as count FROM matches")->fetch_assoc()['count'];
$total_wins = $conn->query("SELECT COUNT(id) as count FROM matches WHERE result = 'Win'")->fetch_assoc()['count'];
$win_rate = ($total_matches > 0) ? round(($total_wins / $total_matches) * 100) : 0;

// Fetch Top Players (Leaderboard) - Example query
$leaderboard_query = "
    SELECT u.name, SUM(s.kills) as total_kills, AVG(s.damage) as avg_damage, SUM(s.mvp) as total_mvp
    FROM stats s
    JOIN users u ON s.user_id = u.id
    GROUP BY s.user_id
    ORDER BY total_kills DESC
    LIMIT 10
";
$leaderboard = $conn->query($leaderboard_query);
?>

<div class="p-4 space-y-6">
    <!-- Guild Overall Stats -->
    <div class="grid grid-cols-3 gap-4 text-center">
        <div class="bg-gray-800 p-4 rounded-lg">
            <p class="text-2xl font-bold"><?php echo $total_matches; ?></p>
            <p class="text-sm text-gray-400">Matches Played</p>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg">
            <p class="text-2xl font-bold text-green-400"><?php echo $total_wins; ?></p>
            <p class="text-sm text-gray-400">Total Wins</p>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg">
            <p class="text-2xl font-bold text-amber-400"><?php echo $win_rate; ?>%</p>
            <p class="text-sm text-gray-400">Win Rate</p>
        </div>
    </div>

    <!-- Basic Chart (using Tailwind CSS for bars) -->
    <div class="bg-gray-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Match Results Overview</h3>
        <div class="space-y-2">
            <div class="flex items-center">
                <span class="w-16 text-sm">Wins</span>
                <div class="w-full bg-gray-700 rounded-full h-4">
                    <div class="bg-green-500 h-4 rounded-full" style="width: <?php echo $win_rate; ?>%"></div>
                </div>
                <span class="w-12 text-sm text-right"><?php echo $win_rate; ?>%</span>
            </div>
             <div class="flex items-center">
                <span class="w-16 text-sm">Losses</span>
                <div class="w-full bg-gray-700 rounded-full h-4">
                    <div class="bg-red-500 h-4 rounded-full" style="width: <?php echo 100 - $win_rate; ?>%"></div>
                </div>
                 <span class="w-12 text-sm text-right"><?php echo 100 - $win_rate; ?>%</span>
            </div>
        </div>
    </div>

    <!-- Player Leaderboard -->
    <div class="bg-gray-800 rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-4">Top Players (Kills)</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase">
                    <tr>
                        <th class="py-2 px-2">#</th>
                        <th class="py-2 px-2">Player</th>
                        <th class="py-2 px-2 text-center">Kills</th>
                        <th class="py-2 px-2 text-center">Avg Dmg</th>
                        <th class="py-2 px-2 text-center">MVPs</th>
                    </tr>
                </thead>
                <tbody class="text-white">
                    <?php if ($leaderboard->num_rows > 0): $rank = 1; ?>
                        <?php while($player = $leaderboard->fetch_assoc()): ?>
                        <tr class="border-b border-gray-700">
                            <td class="py-3 px-2"><?php echo $rank++; ?></td>
                            <td class="py-3 px-2 font-medium"><?php echo htmlspecialchars($player['name']); ?></td>
                            <td class="py-3 px-2 text-center font-bold text-amber-400"><?php echo $player['total_kills']; ?></td>
                            <td class="py-3 px-2 text-center"><?php echo round($player['avg_damage']); ?></td>
                            <td class="py-3 px-2 text-center"><?php echo $player['total_mvp']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4">No stats data available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'common/bottom.php'; ?>