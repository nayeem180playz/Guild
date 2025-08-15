<?php
// admin/applications.php
require_once '../common/config.php';
// Admin session check here
$page_title = "Manage Applications";
include 'common/header.php';

// Fetch applications from DB
$applications = $conn->query("SELECT * FROM applications WHERE status = 'Pending' ORDER BY created_at DESC");
?>

<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Pending Join Requests</h2>
    
    <div id="applications-list" class="space-y-4">
        <?php if ($applications->num_rows > 0): ?>
            <?php while($app = $applications->fetch_assoc()): ?>
            <div id="app-card-<?php echo $app['id']; ?>" class="bg-gray-800 rounded-lg p-4 shadow-md">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg text-amber-400"><?php echo htmlspecialchars($app['name']); ?></h3>
                        <p class="text-sm text-gray-400">UID: <?php echo htmlspecialchars($app['uid']); ?></p>
                        <p class="text-sm text-gray-400">Age: <?php echo $app['age']; ?></p>
                        <p class="text-sm text-gray-400">Role Pref: <?php echo htmlspecialchars($app['role_pref']); ?></p>
                    </div>
                    <span class="text-xs text-gray-500"><?php echo date("M j, Y", strtotime($app['created_at'])); ?></span>
                </div>
                <?php if (!empty($app['achievements'])): ?>
                <div class="mt-3 border-t border-gray-700 pt-3">
                    <p class="text-sm font-semibold text-gray-300">Achievements:</p>
                    <p class="text-sm text-gray-400 whitespace-pre-wrap"><?php echo htmlspecialchars($app['achievements']); ?></p>
                </div>
                <?php endif; ?>
                <div class="mt-4 flex justify-end space-x-3">
                    <button class="reject-btn bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg" data-id="<?php echo $app['id']; ?>">
                        <i class="fas fa-times mr-1"></i> Reject
                    </button>
                    <button class="accept-btn bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg" data-id="<?php echo $app['id']; ?>">
                        <i class="fas fa-check mr-1"></i> Accept
                    </button>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p id="no-apps-msg" class="text-center text-gray-500 py-8">No pending applications found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Loading Modal (re-use if you have it in a common file) -->
<div id="loading-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <i class="fas fa-spinner fa-spin text-white text-5xl"></i>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const applicationsList = document.getElementById('applications-list');
    const loadingModal = document.getElementById('loading-modal');

    const handleApplication = async (id, newStatus) => {
        if (!confirm(`Are you sure you want to ${newStatus.toLowerCase()} this application?`)) {
            return;
        }

        loadingModal.classList.remove('hidden');

        const formData = new FormData();
        formData.append('id', id);
        formData.append('status', newStatus);

        try {
            const response = await fetch('api_applications.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            alert(result.message);

            if (result.success) {
                const card = document.getElementById(`app-card-${id}`);
                if (card) {
                    card.style.transition = 'opacity 0.5s';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                        // Check if no applications are left
                        if (applicationsList.children.length === 1 && applicationsList.children[0].id === 'no-apps-msg') {
                             // This is not quite right, better check count
                        }
                         const remainingCards = applicationsList.querySelectorAll('div[id^="app-card-"]').length;
                         if (remainingCards === 0) {
                             document.getElementById('no-apps-msg').style.display = 'block';
                         }
                    }, 500);
                }
            }
        } catch (error) {
            alert('An error occurred while processing the application.');
        } finally {
            loadingModal.classList.add('hidden');
        }
    };

    applicationsList.addEventListener('click', function(e) {
        const acceptBtn = e.target.closest('.accept-btn');
        const rejectBtn = e.target.closest('.reject-btn');

        if (acceptBtn) {
            handleApplication(acceptBtn.dataset.id, 'Accepted');
        } else if (rejectBtn) {
            handleApplication(rejectBtn.dataset.id, 'Rejected');
        }
    });
});