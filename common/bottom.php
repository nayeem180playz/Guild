<?php
// common/bottom.php
?>
    </main>
    <!-- Bottom Navigation Bar -->
    <nav class="fixed bottom-0 left-0 right-0 bg-gray-800 border-t border-gray-700 flex justify-around p-2 z-40">
        <a href="index.php" class="flex flex-col items-center text-gray-400 hover:text-amber-400 w-1/4">
            <i class="fas fa-home text-xl"></i><span class="text-xs mt-1">Home</span>
        </a>
        <a href="events.php" class="flex flex-col items-center text-gray-400 hover:text-amber-400 w-1/4">
            <i class="fas fa-calendar-alt text-xl"></i><span class="text-xs mt-1">Events</span>
        </a>
        <a href="members.php" class="flex flex-col items-center text-gray-400 hover:text-amber-400 w-1/4">
            <i class="fas fa-users text-xl"></i><span class="text-xs mt-1">Members</span>
        </a>
        <a href="profile.php" class="flex flex-col items-center text-gray-400 hover:text-amber-400 w-1/4">
            <i class="fas fa-user text-xl"></i><span class="text-xs mt-1">Profile</span>
        </a>
    </nav>
    
    <!-- JavaScript for Interactivity -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarBtn = document.getElementById('sidebar-btn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            if (sidebarBtn && sidebar && sidebarOverlay) {
                sidebarBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                    sidebarOverlay.classList.toggle('hidden');
                });

                sidebarOverlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                });
            }

            const viewButtons = document.querySelectorAll('button');
            viewButtons.forEach(button => {
                if (button.textContent.trim() === 'View') {
                    button.addEventListener('click', () => {
                        window.location.href = 'events.php';
                    });
                }
            });
        });
    </script>
</body>
</html>