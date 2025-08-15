<?php
// admin/events.php
require_once '../common/config.php';
// Admin session check here
$page_title = "Manage Events";
include 'common/header.php';
?>

<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Events Management</h2>
        <button id="add-event-btn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg"><i class="fas fa-plus"></i> Add Event</button>
    </div>

    <!-- Events Table -->
    <div class="bg-gray-800 rounded-lg overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-300">
            <thead class="text-xs text-gray-400 uppercase bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Date</th>
                    <th scope="col" class="px-6 py-3">Type</th>
                    <th scope="col" class="px-6 py-3">Prize (₹)</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody id="events-table-body">
                <!-- Data will be loaded via AJAX -->
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Event Modal -->
<div id="event-modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md m-4">
        <form id="event-form" class="p-6 space-y-4">
            <h3 id="modal-title" class="text-lg font-bold text-amber-400">Add New Event</h3>
            <input type="hidden" id="event-id" name="id">
            <div>
                <label for="name" class="block mb-2 text-sm font-medium">Event Name</label>
                <input type="text" id="name" name="name" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
            </div>
            <div>
                <label for="date" class="block mb-2 text-sm font-medium">Date and Time</label>
                <input type="datetime-local" id="date" name="date" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
            </div>
            <div>
                <label for="type" class="block mb-2 text-sm font-medium">Type</label>
                <select id="type" name="type" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5">
                    <option value="Scrim">Scrim</option>
                    <option value="Tournament">Tournament</option>
                </select>
            </div>
             <div>
                <label for="prize" class="block mb-2 text-sm font-medium">Prize Pool (₹)</label>
                <input type="number" id="prize" name="prize" value="0" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" required>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" id="cancel-btn" class="py-2 px-4 bg-gray-600 hover:bg-gray-700 rounded-lg">Cancel</button>
                <button type="submit" class="py-2 px-4 bg-amber-600 hover:bg-amber-700 rounded-lg">Save Event</button>
            </div>
        </form>
    </div>
</div>

<!-- Loading Modal -->
<div id="loading-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <i class="fas fa-spinner fa-spin text-white text-5xl"></i>
</div>

<script>// This code goes inside the <script> tag of admin/events.php

document.addEventListener('DOMContentLoaded', function() {
    const addEventBtn = document.getElementById('add-event-btn');
    const modal = document.getElementById('event-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const eventForm = document.getElementById('event-form');
    const modalTitle = document.getElementById('modal-title');
    const eventsTableBody = document.getElementById('events-table-body');
    const loadingModal = document.getElementById('loading-modal');

    // Show/hide loading modal
    const showLoading = (show) => {
        loadingModal.classList.toggle('hidden', !show);
    };

    // Show/hide form modal
    const showModal = (show) => {
        modal.classList.toggle('hidden', !show);
    };

    // Fetch and render events
    const loadEvents = async () => {
        showLoading(true);
        try {
            const response = await fetch('api_events.php');
            const result = await response.json();
            if (result.status === 'success') {
                eventsTableBody.innerHTML = '';
                result.data.forEach(event => {
                    const prize = new Intl.NumberFormat('en-IN').format(event.prize);
                    const eventDate = new Date(event.date).toLocaleString('en-US', {
                        year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
                    });
                    const row = `
                        <tr class="border-b border-gray-700">
                            <td class="px-6 py-4 font-medium">${event.name}</td>
                            <td class="px-6 py-4">${eventDate}</td>
                            <td class="px-6 py-4">${event.type}</td>
                            <td class="px-6 py-4">₹${prize}</td>
                            <td class="px-6 py-4">
                                <button class="edit-btn text-blue-400 hover:text-blue-600 mr-2" data-id="${event.id}" data-name="${event.name}" data-date="${event.date}" data-type="${event.type}" data-prize="${event.prize}"><i class="fas fa-edit"></i></button>
                                <button class="delete-btn text-red-400 hover:text-red-600" data-id="${event.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                    eventsTableBody.insertAdjacentHTML('beforeend', row);
                });
            }
        } catch (error) {
            eventsTableBody.innerHTML = '<tr><td colspan="5" class="text-center p-4">Failed to load data.</td></tr>';
        } finally {
            showLoading(false);
        }
    };

    // Open modal for adding a new event
    addEventBtn.addEventListener('click', () => {
        eventForm.reset();
        document.getElementById('event-id').value = '';
        modalTitle.textContent = 'Add New Event';
        showModal(true);
    });

    // Close modal
    cancelBtn.addEventListener('click', () => showModal(false));
    modal.addEventListener('click', (e) => {
        if (e.target === modal) showModal(false);
    });

    // Handle form submission (Create/Update)
    eventForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        showLoading(true);
        const formData = new FormData(eventForm);
        try {
            const response = await fetch('api_events.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            alert(result.message);
            if (result.status === 'success') {
                showModal(false);
                loadEvents();
            }
        } catch (error) {
            alert('An error occurred.');
        } finally {
            showLoading(false);
        }
    });

    // Handle Edit and Delete button clicks
    eventsTableBody.addEventListener('click', async (e) => {
        // Edit button
        if (e.target.closest('.edit-btn')) {
            const btn = e.target.closest('.edit-btn');
            document.getElementById('event-id').value = btn.dataset.id;
            document.getElementById('name').value = btn.dataset.name;
            // Format date for datetime-local input
            const rawDate = new Date(btn.dataset.date);
            const formattedDate = rawDate.toISOString().slice(0, 16);
            document.getElementById('date').value = formattedDate;
            document.getElementById('type').value = btn.dataset.type;
            document.getElementById('prize').value = btn.dataset.prize;
            modalTitle.textContent = 'Edit Event';
            showModal(true);
        }
        
        // Delete button
        if (e.target.closest('.delete-btn')) {
            const btn = e.target.closest('.delete-btn');
            if (confirm('Are you sure you want to delete this event?')) {
                showLoading(true);
                try {
                    const response = await fetch('api_events.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id=${btn.dataset.id}`
                    });
                    const result = await response.json();
                    alert(result.message);
                    if (result.status === 'success') {
                        loadEvents();
                    }
                } catch (error) {
                    alert('An error occurred.');
                } finally {
                    showLoading(false);
                }
            }
        }
    });

    // Initial load
    loadEvents();
});
// JavaScript for AJAX CRUD operations will go here.
// It will handle:
// 1. Fetching and displaying events.
// 2. Showing the modal to add/edit events.
// 3. Submitting the form via AJAX.
// 4. Deleting events with confirmation.
// All actions will communicate with a new `api_events.php` file.
</script>

<?php include 'common/bottom.php'; ?>