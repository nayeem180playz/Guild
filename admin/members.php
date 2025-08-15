<?php
// admin/members.php (Corrected Version)
$page_title = "Manage Members";
include 'common/header.php';
$members = $conn->query("SELECT id, name, uid, email, role, join_date FROM users ORDER BY role, name");
?>

<div class="p-6">
    <div class="bg-gray-800 rounded-lg overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-300">
            <thead class="text-xs text-gray-400 uppercase bg-gray-700">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">UID</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if($members && $members->num_rows > 0): ?>
                <?php while($member = $members->fetch_assoc()): ?>
                    <tr class="border-b border-gray-700">
                        <td class="px-6 py-4 font-medium"><?php echo htmlspecialchars($member['name']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($member['uid']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($member['role']); ?></td>
                        <td class="px-6 py-4">
                            <button class="text-blue-400 hover:text-blue-600 mr-4"><i class="fas fa-edit"></i> Edit</button>
                            <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center p-4">No members found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'common/bottom.php'; ?>