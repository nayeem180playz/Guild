<?php
// admin/common/bottom.php
?>
            </main>
        </div>
    </div>
    <!-- JavaScript for Admin Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarBtn = document.getElementById('admin-sidebar-btn');
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('admin-sidebar-overlay');

            if (sidebarBtn && sidebar && overlay) {
                sidebarBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                });

                overlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }
        });
    </script>
</body>
</html>
