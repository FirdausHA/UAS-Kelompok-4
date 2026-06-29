<?php
session_start();

require_once '../../config/database.php';
require_once '../../models/Order.php';
require_once '../../includes/auth_guard.php';
require_once '../../includes/helpers.php';

requireAdmin();

$database = new Database();
$db = $database->getConnection();
$orderModel = new Order($db);

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$orders = [];

try {
    $orders = $orderModel->getAllForAdmin($search);
} catch (Exception $e) {
    $orders = [];
}

$page_title = 'Order Management | Admin';
$admin_page = 'orders';
$status = $_GET['status'] ?? '';

include '../../includes/admin/header.php';
?>

<header class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Order Management</h1>
        <p class="admin-page-subtitle">Manage and review studio bookings.</p>
    </div>
    <form class="admin-toolbar admin-toolbar-inline" method="get">
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search order, customer..." class="admin-search">
        <button type="submit" class="btn-icon" aria-label="Search">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </button>
    </form>
</header>

<?php if ($status === 'updated'): ?>
<div class="alert alert-success admin-alert">Status order berhasil diperbarui.</div>
<?php endif; ?>

<section class="admin-page-content">
    <div class="table-wrap">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Studio & Schedule</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Payment Proof</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($orders) > 0): ?>
                    <?php foreach ($orders as $order):
                        $initials = userInitials($order['customer_nama']);
                        $buktiUrl = '../../uploads/bukti/' . rawurlencode($order['bukti_file']);
                    ?>
                    <tr>
                        <td><strong>#<?= htmlspecialchars($order['order_code']) ?></strong></td>
                        <td>
                            <div class="user-cell">
                                <span class="user-avatar-sm"><?= htmlspecialchars($initials) ?></span>
                                <div>
                                    <div><?= htmlspecialchars($order['customer_nama']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($order['customer_email']) ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div><?= htmlspecialchars($order['studio_nama']) ?></div>
                            <small class="text-muted"><?= formatTanggalOrder($order['tanggal']) ?> • <?= str_replace('-', ' - ', htmlspecialchars($order['waktu'])) ?></small>
                        </td>
                        <td><?= formatRupiah($order['total']) ?></td>
                        <td>
                            <span class="badge badge-order-<?= htmlspecialchars($order['status']) ?>">
                                <?= orderStatusAdminLabel($order['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!empty($order['bukti_file'])): ?>
                            <a href="<?= $buktiUrl ?>" target="_blank" class="bukti-thumb-link">
                                <img src="<?= $buktiUrl ?>" alt="Bukti" class="bukti-thumb" onerror="this.outerHTML='Verified'">
                            </a>
                            <?php else: ?>
                            —
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="../../controllers/OrderController.php?action=update_status" method="POST" class="status-form">
                                <input type="hidden" name="id" value="<?= (int) $order['id'] ?>">
                                <select name="status" class="admin-select admin-select-sm" onchange="this.form.submit()">
                                    <?php foreach (['pending','confirmed','completed','cancelled'] as $st): ?>
                                    <option value="<?= $st ?>"<?= $order['status'] === $st ? ' selected' : '' ?>><?= orderStatusAdminLabel($st) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted">Belum ada pesanan masuk.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <p class="inventory-count">Showing <?= count($orders) ?> orders</p>
</section>

<?php include '../../includes/admin/footer.php'; ?>
