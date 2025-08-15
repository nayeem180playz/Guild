<?php
// members.php
require_once 'common/config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$page_title = "Guild Members";
include 'common/header.php';

// Fetch members from DB
$members = $conn->query("SELECT name, uid, role, join_date FROM users ORDER BY role, name");
?>

<div class="p-4">
    <!-- Search and Filter -->
    <div class="mb-4">
        <input type="text" id="search-member" class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-lg p-2.5" placeholder="Search by name or UID...">
    </div>

    <!-- Members Grid -->
    <div id="members-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        <?php if ($members->num_rows > 0): ?>
            <?php while($member = $members->fetch_assoc()): ?>
            <div class="member-card bg-gray-800 rounded-lg p-3 text-center shadow-md flex flex-col items-center" data-name="<?php echo strtolower(htmlspecialchars($member['name'])); ?>" data-uid="<?php echo htmlspecialchars($member['uid']); ?>">
                <img src="https://placehold.co/80x80/000000/FFD700?text=<?php echo substr($member['name'], 0, 1); ?>" alt="Avatar" class="w-16 h-16 rounded-full mb-2 border-2 border-amber-400">
                <h4 class="font-bold text-white truncate w-full" title="<?php echo htmlspecialchars($member['name']); ?>"><?php echo htmlspecialchars($member['name']); ?></h4>
                <p class="text-xs text-gray-400">UID: <?php echo htmlspecialchars($member['uid']); ?></p>
                <?php
                    $role_color = 'bg-gray-600';
                    if ($member['role'] === 'Leader') $role_color = 'bg-amber-500';
                    if ($member['role'] === 'Co-Leader') $role_color = 'bg-sky-500';
                ?>
                <span class="mt-2 text-xs font-semibold <?php echo $role_color; ?> text-white px-2 py-1 rounded-full">
                    <?php echo htmlspecialchars($member['role']); ?>
                </span>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="col-span-full text-center text-gray-500">No members found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('search-member').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const memberCards = document.querySelectorAll('.member-card');

    memberCards.forEach(card => {
        const name = card.dataset.name;
        const uid = card.dataset.uid;
        if (name.includes(searchTerm) || uid.includes(searchTerm)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>

<?php include 'common/bottom.php'; ?>