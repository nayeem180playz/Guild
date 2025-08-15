<?php
// match_schedule.php
require_once 'common/config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Validate event_id
if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    header("Location: events.php");
    exit();
}
$event_id = intval($_GET['event_id']);

// Fetch event and matches
$event_stmt = $conn->prepare("SELECT name FROM events WHERE id = ?");
$event_stmt->bind_param("i", $event_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();
if ($event_result->num_rows === 0) {
    header("Location: events.php");
    exit();
}
$event = $event_result->fetch_assoc();
$page_title = "Schedule: " . htmlspecialchars($event['name']);
include 'common/header.php';

$matches_stmt = $conn->prepare("SELECT * FROM matches WHERE event_id = ? ORDER BY time ASC");
$matches_stmt->bind_param("i", $event_id);
$matches_stmt->execute();
$matches = $matches_stmt->get_result();
?>

<div class="p-4">
    <h2 class="text-xl font-semibold mb-4">Match Schedule</h2>
    
    <div class="space-y-4">
        <?php if ($matches->num_rows > 0): ?>
            <?php while($match = $matches->fetch_assoc()): ?>
            <div class="bg-gray-800 rounded-lg p-4 shadow-md">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-bold text-lg">vs <?php echo htmlspecialchars($match['opponent']); ?></p>
                        <p class="text-sm text-gray-400"><i class="fas fa-calendar-alt fa-fw mr-1"></i> <?php echo date("D, M j, Y, g:i A", strtotime($match['time'])); ?></p>
                        <p class="text-sm text-gray-400"><i class="fas fa-map-marker-alt fa-fw mr-1"></i> <?php echo htmlspecialchars($match['map']); ?> | <?php echo htmlspecialchars($match['mode']); ?></p>
                    </div>
                    <?php if ($match['result']): 
                        $result_color = 'bg-gray-500';
                        if ($match['result'] === 'Win') $result_color = 'bg-green-500';
                        if ($match['result'] === 'Loss') $result_color = 'bg-red-500';
                    ?>
                        <div class="text-center">
                            <span class="font-bold text-lg <?php echo str_replace('bg', 'text', $result_color); ?>"><?php echo $match['result']; ?></span>
                        </div>
                    <?php else: ?>
                        <span class="text-sm font-semibold bg-blue-600 text-white px-3 py-1 rounded-full">Upcoming</span>
                    <?php endif; ?>
                </div>
                <?php if ($match['result']): ?>
                <div class="mt-3">
                    <a href="match_detail.php?match_id=<?php echo $match['id']; ?>" class="w-full text-center block bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 rounded-lg transition duration-300">
                        View Details
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-gray-500 py-8">No matches scheduled for this event yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'common/bottom.php'; ?>