<?php
session_start();

require_once '../../config/database.php';
require_once '../../models/User.php';
require_once '../../includes/auth_guard.php';
require_once '../../includes/helpers.php';

requireAdmin();

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$roleFilter = isset($_GET['role']) ? trim($_GET['role']) : 'all';
$users = $userModel->getAllForAdmin($search, $roleFilter);

$totalUsers = $userModel->countByRole('pelanggan');
$activeUsers = $userModel->countActivePelanggan();
$newWeek = $userModel->countNewThisWeek();

$page_title = 'User Management | Admin';
$admin_page = 'users';
$status = $_GET['status'] ?? '';

include '../../includes/admin/header.php';
?>

<header class="admin-topbar">
    <div>
        <h1 class="admin-page-title">User Management</h1>
        <p class="admin-page-subtitle">Overview and control of all registered users on the Obsidian Studio platform.</p>
    </div>
</header>

<?php if ($status === 'updated'): ?>
<div class="alert alert-success admin-alert">Status pengguna berhasil diperbarui.</div>
<?php endif; ?>

<section class="admin-metrics admin-metrics-compact">
    <div class="metric-card">
        <div><p class="metric-label">Total Users</p><p class="metric-value"><?= $totalUsers ?></p></div>
    </div>
    <div class="metric-card">
        <div><p class="metric-label">Active Users</p><p class="metric-value"><?= $activeUsers ?></p></div>
    </div>
    <div class="metric-card">
        <div><p class="metric-label">New This Week</p><p class="metric-value"><?= $newWeek ?></p></div>
    </div>
</section>

<section class="admin-page-content">
    <form class="admin-toolbar" method="get">
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search users by name or email..." class="admin-search">
        <select name="role" class="admin-select">
            <option value="all"<?= $roleFilter === 'all' ? ' selected' : '' ?>>All Roles</option>
            <option value="pelanggan"<?= $roleFilter === 'pelanggan' ? ' selected' : '' ?>>Customer</option>
            <option value="admin"<?= $roleFilter === 'admin' ? ' selected' : '' ?>>Admin</option>
        </select>
        <button type="submit" class="btn btn-admin-add">Filter</button>
    </form>

    <div class="table-wrap">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user):
                        $initials = userInitials($user['nama_lengkap']);
                        $isActive = ($user['status_akun'] ?? 'active') === 'active';
                    ?>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <span class="user-avatar-sm"><?= htmlspecialchars($initials) ?></span>
                                <?= htmlspecialchars($user['nama_lengkap']) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= $user['role'] === 'admin' ? 'Admin' : 'Customer' ?></td>
                        <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <?php if ($user['role'] === 'pelanggan'): ?>
                            <span class="badge badge-<?= $isActive ? 'available' : 'booked' ?>"><?= $isActive ? 'Active' : 'Blocked' ?></span>
                            <?php else: ?>
                            <span class="badge badge-available">Active</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['role'] === 'pelanggan'): ?>
                            <a href="../../controllers/UserController.php?action=toggle_status&id=<?= (int) $user['id'] ?>"
                               class="btn-icon" title="Toggle status"
                               onclick="return confirm('Ubah status pengguna ini?')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                            </a>
                            <?php else: ?>
                            —
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted">Tidak ada pengguna ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <p class="inventory-count">Showing <?= count($users) ?> users</p>
</section>

<?php include '../../includes/admin/footer.php'; ?>
