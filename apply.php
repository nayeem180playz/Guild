<?php
// apply.php
require_once 'common/config.php';
$page_title = "Apply to Guild";
include 'common/header.php';
?>

<div class="p-4">
    <div class="max-w-lg mx-auto bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-center text-amber-400 mb-6">Guild Application Form</h2>
        <form id="application-form" class="space-y-4">
            <div>
                <label for="name" class="block mb-2 text-sm font-medium">In-Game Name</label>
                <input type="text" id="name" name="name" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
            </div>
             <div>
                <label for="uid" class="block mb-2 text-sm font-medium">Game UID</label>
                <input type="number" id="uid" name="uid" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
            </div>
            <div>
                <label for="age" class="block mb-2 text-sm font-medium">Age</label>
                <input type="number" id="age" name="age" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
            </div>
            <div>
                <label for="role_pref" class="block mb-2 text-sm font-medium">Role Preference (e.g., Rusher, Sniper)</label>
                <input type="text" id="role_pref" name="role_pref" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
            </div>
            <div>
                <label for="achievements" class="block mb-2 text-sm font-medium">Achievements (Optional)</label>
                <textarea id="achievements" name="achievements" rows="3" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5"></textarea>
            </div>
            <button type="submit" class="w-full text-white bg-amber-600 hover:bg-amber-700 font-medium rounded-lg text-sm px-5 py-3 text-center">Submit Application</button>
            <div id="feedback" class="text-sm text-center mt-2"></div>
        </form>
    </div>
</div>

<script>
document.getElementById('application-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const feedback = document.getElementById('feedback');
    feedback.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Submitting...`;
    
    const formData = new FormData(this);

    try {
        // We can create a simple API endpoint for this
        const response = await fetch('api_apply.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        
        feedback.textContent = result.message;
        feedback.className = result.success ? 'text-green-400' : 'text-red-400';
        
        if (result.success) {
            document.getElementById('application-form').reset();
        }
    } catch (error) {
        feedback.textContent = 'An error occurred. Please try again.';
        feedback.className = 'text-red-400';
    }
});
</script>

<?php include 'common/bottom.php'; ?>