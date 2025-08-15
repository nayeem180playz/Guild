<?php
// admin/matches.php (Updated with new modes)
$page_title = "Manage Matches";
include 'common/header.php';
$events = $conn->query("SELECT id, name FROM events ORDER BY date DESC");
?>

<div class="p-6">
    <div class="mb-4">
        <label for="event-selector" class="block mb-2 text-sm">Select Event to Manage Matches</label>
        <select id="event-selector" class="bg-gray-700 w-full p-2.5 rounded-lg">
            <option value="">-- Select Event --</option>
            <?php while($event = $events->fetch_assoc()): ?>
                <option value="<?php echo $event['id']; ?>"><?php echo htmlspecialchars($event['name']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div id="matches-container" class="hidden">
        <div class="flex justify-between items-center mb-4">
            <h3 id="matches-header" class="text-lg font-semibold">Matches</h3>
            <button id="add-match-btn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg"><i class="fas fa-plus"></i> Add Match</button>
        </div>
        <div class="bg-gray-800 rounded-lg overflow-x-auto">
            <table class="w-full text-sm text-left"><tbody id="matches-table-body"></tbody></table>
        </div>
    </div>
</div>

<div id="match-modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md m-4">
        <form id="match-form" class="p-6 space-y-4">
            <h3 id="modal-title" class="text-lg font-bold">Add New Match</h3>
            <input type="hidden" id="match-id" name="id"><input type="hidden" id="event-id-field" name="event_id">
            <input type="text" name="opponent" placeholder="Opponent Name" class="bg-gray-700 w-full p-2.5 rounded-lg" required>
            <input type="datetime-local" name="time" class="bg-gray-700 w-full p-2.5 rounded-lg text-gray-400" required>
            <input type="text" name="map" placeholder="Map (e.g., Bermuda)" class="bg-gray-700 w-full p-2.5 rounded-lg">
            <select name="mode" class="bg-gray-700 w-full p-2.5 rounded-lg" required>
                <option value="">-- Select Mode --</option><option value="Battle Royale">Battle Royale</option><option value="Clash Squad">Clash Squad</option><option value="Lone Wolf">Lone Wolf</option><option value="Custom">Custom</option>
            </select>
            <select name="result" class="bg-gray-700 w-full p-2.5 rounded-lg">
                <option value="">-- Select Result --</option><option value="Win">Win</option><option value="Loss">Loss</option><option value="Draw">Draw</option>
            </select>
            <div class="flex justify-end space-x-4">
                <button type="button" class="py-2 px-4 bg-gray-600 rounded-lg" onclick="document.getElementById('match-modal').classList.add('hidden')">Cancel</button>
                <button type="submit" class="py-2 px-4 bg-amber-600 rounded-lg">Save Match</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventSelector = document.getElementById('event-selector');
    const matchesContainer = document.getElementById('matches-container');
    const matchesTableBody = document.getElementById('matches-table-body');
    const addMatchBtn = document.getElementById('add-match-btn');
    const modal = document.getElementById('match-modal');
    const matchForm = document.getElementById('match-form');
    
    eventSelector.addEventListener('change', async function() {
        const eventId = this.value;
        document.getElementById('event-id-field').value = eventId;
        if (eventId) {
            matchesContainer.classList.remove('hidden');
            const response = await fetch(`api_matches.php?event_id=${eventId}`);
            const result = await response.json();
            matchesTableBody.innerHTML = '';
            if (result.status === 'success' && result.data.length > 0) {
                result.data.forEach(match => {
                    matchesTableBody.innerHTML += `<tr><td class="px-6 py-4">${match.opponent}</td><td class="px-6 py-4">${match.mode}</td><td class="px-6 py-4">${match.result || 'N/A'}</td></tr>`;
                });
            } else {
                matchesTableBody.innerHTML = '<tr><td colspan="4" class="text-center p-4">No matches found.</td></tr>';
            }
        } else {
            matchesContainer.classList.add('hidden');
        }
    });

    addMatchBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    
    matchForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const response = await fetch('api_matches.php', { method: 'POST', body: formData });
        const result = await response.json();
        alert(result.message);
        if (result.status === 'success') {
            modal.classList.add('hidden');
            eventSelector.dispatchEvent(new Event('change')); // Refresh the list
        }
    });
});
</script>
<?php include 'common/bottom.php'; ?>
