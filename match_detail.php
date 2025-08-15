<?php
// match_detail.php
require_once 'common/config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['match_id']) || !is_numeric($_GET['match_id'])) {
    header("Location: events.php");
    exit();
}
$match_id = intval($_GET['match_id']);

// Fetch match details
$match_stmt = $conn->prepare("SELECT m.*, e.name as event_name FROM matches m JOIN events e ON m.event_id = e.id WHERE m.id = ?");
$match_stmt->bind_param("i", $match_id);
$match_stmt->execute();
$match_result = $match_stmt->get_result();
if ($match_result->num_rows === 0) {
    header("Location: events.php");
    exit();
}
$match = $match_result->fetch_assoc();
$page_title = "Match Details";
include 'common/header.php';

// Fetch player stats for this match
$stats_query = "
    SELECT u.name, s.kills, s.damage, s.survival_time, s.mvp
    FROM stats s
    JOIN users u ON s.user_id = u.id
    WHERE s.match_id = ?
    ORDER BY s.kills DESC, s.damage DESC
";
$stats_stmt = $conn->prepare($stats_query);
$stats_stmt->bind_param("i", $match_id);
$stats_stmt->execute();
$stats = $stats_stmt->get_result();
?>

<div class="p-4 space-y-6">
    <!-- Match Info -->
    <div class="bg-gray-800 rounded-lg p-6 shadow-lg text-center">
        <p class="text-sm text-gray-400"><?php echo htmlspecialchars($match['event_name']); ?></p>
        <h2 class="text-2xl font-bold">vs <?php echo htmlspecialchars($match['opponent']); ?></h2>
        <?php
            $result_color = 'text-gray-400';
            if ($match['result'] === 'Win') $result_color = 'text-green-400';
            if ($match['result'] === 'Loss') $result_color = 'text-red-400';
        ?>
        <p class="text-3xl font-extrabold mt-2 <?php echo $result_color; ?>"><?php echo $match['result']; ?></p>
        <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($match['map']); ?> | <?php echo htmlspecialchars($match['mode']); ?></p>
    </div>

    <!-- Player Performance -->
    <div class="bg-gray-800 rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-4">Player Performance</h3>
        <div class="space-y-3">
            <?php if ($stats->num_rows > 0): ?>
                <?php while ($player = $stats->fetch_assoc()): ?>
                <div class="flex items-center bg-gray-700 p-3 rounded-lg">
                    <img src="https://placehold.co/40x40/000000/FFD700?text=<?php echo substr($player['name'], 0, 1); ?>" class="w-10 h-10 rounded-full mr-3">
                    <div class="flex-grow">
                        <p class="font-semibold text-white">
                            <?php echo htmlspecialchars($player['name']); ?>
                            <?php if ($player['mvp']): ?>
                                <span class="ml-2 text-xs font-bold bg-amber-500 text-white px-2 py-0.5 rounded-full">MVP</span>
                            <?php endif; ?>
                        </p>
                        <div class="flex text-xs text-gray-400 space-x-3">
                            <span>Kills: <b class="text-white"><?php echo $player['kills']; ?></b></span>
                            <span>Damage: <b class="text-white"><?php echo number_format($player['damage']); ?></b></span>
                            <span>Survival: <b class="text-white"><?php echo round($player['survival_time'] / 60, 1); ?>m</b></span>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-gray-500 py-4">No player stats available for this match.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'common/bottom.php'; ?>