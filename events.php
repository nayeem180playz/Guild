<?php
// events.php
require_once 'common/config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$page_title = "Events";
include 'common/header.php';

// Fetch events from DB
$events = $conn->query("SELECT * FROM events ORDER BY date DESC");
?>

<div class="p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Upcoming Events</h2>
        <!-- Filter Buttons -->
        <div>
            <select id="type-filter" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg p-2">
                <option value="all">All Types</option>
                <option value="Scrim">Scrim</option>
                <option value="Tournament">Tournament</option>
            </select>
        </div>
    </div>

    <!-- Events List -->
    <div id="events-list" class="space-y-4">
        <?php if ($events->num_rows > 0): ?>
            <?php while($event = $events->fetch_assoc()): ?>
            <div class="bg-gray-800 rounded-lg p-4 shadow-md event-card" data-type="<?php echo $event['type']; ?>">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg text-amber-400"><?php echo htmlspecialchars($event['name']); ?></h3>
                        <p class="text-sm text-gray-400">
                            <i class="fas fa-calendar-alt fa-fw mr-1"></i> <?php echo date("D, M j, Y, g:i A", strtotime($event['date'])); ?>
                        </p>
                        <p class="text-sm text-gray-400">
                            <i class="fas fa-trophy fa-fw mr-1"></i> Type: <?php echo htmlspecialchars($event['type']); ?>
                        </p>
                         <p class="text-sm text-gray-300">
                            <i class="fas fa-rupee-sign fa-fw mr-1"></i> Prize: ₹<?php echo number_format($event['prize']); ?>
                        </p>
                    </div>
                    <span class="text-xs font-semibold <?php echo $event['type'] == 'Tournament' ? 'bg-red-600' : 'bg-blue-600'; ?> text-white px-2 py-1 rounded-full">
                        <?php echo htmlspecialchars($event['type']); ?>
                    </span>
                </div>
                <div class="mt-4">
                    <a href="match_schedule.php?event_id=<?php echo $event['id']; ?>" class="w-full text-center bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                        View Schedule
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-gray-500">No upcoming events found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('type-filter').addEventListener('change', function() {
    const filterValue = this.value.toLowerCase();
    const eventCards = document.querySelectorAll('.event-card');
    
    eventCards.forEach(card => {
        const eventType = card.getAttribute('data-type').toLowerCase();
        if (filterValue === 'all' || eventType === filterValue) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>

<?php include 'common/bottom.php'; ?>