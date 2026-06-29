<?php
session_start();

$base_path = '../../';

require_once $base_path . 'config/database.php';
require_once $base_path . 'models/User.php';
require_once $base_path . 'models/Order.php';
require_once $base_path . 'includes/auth_guard.php';
require_once $base_path . 'includes/helpers.php';

requirePelanggan();

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);
$orderModel = new Order($db);
$user = $userModel->getById((int) $_SESSION['user_id']);

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$allowedFilters = ['all', 'berjalan', 'selesai', 'batal'];
if (!in_array($filter, $allowedFilters)) $filter = 'all';

$orders = [];
try {
    $orders = $orderModel->getByUser((int) $_SESSION['user_id'], $filter);
} catch (Exception $e) {
    $orders = [];
}

$page_title = 'Riwayat Transaksi | Obsidian Studio';
$nav_mode = 'static';
$extra_css = ['assets/css/pelanggan.css'];
$pelanggan_page = 'riwayat';

include $base_path . 'includes/header.php';
include $base_path . 'includes/pelanggan/layout-start.php';
?>

<header class="riwayat-header">
    <h1 class="riwayat-title">Riwayat Transaksi</h1>
    <p class="riwayat-subtitle">Pantau dan kelola pemesanan studio Anda.</p>
</header>

<div class="filter-tabs">
    <?php
    $tabs = [
        'all' => 'Semua',
        'berjalan' => 'Berjalan',
        'selesai' => 'Selesai',
        'batal' => 'Batal',
    ];
    foreach ($tabs as $key => $label):
    ?>
    <a href="riwayat.php?filter=<?= $key ?>" class="filter-tab<?= $filter === $key ? ' is-active' : '' ?>"><?= $label ?></a>
    <?php endforeach; ?>
</div>

<div class="riwayat-grid">
    <?php if (count($orders) > 0): ?>
        <?php foreach ($orders as $order):
            $st = orderStatusLabel($order['status']);
            $isBerjalan = in_array($order['status'], ['pending', 'confirmed']);
        ?>
        <article class="riwayat-card<?= $isBerjalan ? ' is-highlight' : '' ?>">
            <div class="riwayat-card-top">
                <div>
                    <span class="riwayat-order-code">#<?= htmlspecialchars($order['order_code']) ?></span>
                    <h3 class="riwayat-studio"><?= htmlspecialchars($order['studio_nama']) ?></h3>
                </div>
                <span class="order-status <?= $st['class'] ?>"><?= strtoupper($st['label']) ?></span>
            </div>
            <p class="riwayat-datetime"><?= formatTanggalOrder($order['tanggal']) ?>, <?= str_replace('-', ' - ', htmlspecialchars($order['waktu'])) ?></p>
            <div class="riwayat-card-bottom">
                <div>
                    <span class="riwayat-price-label">Total Biaya</span>
                    <span class="riwayat-price<?= $order['status'] === 'cancelled' ? ' is-cancelled' : '' ?>"><?= formatRupiah($order['total']) ?></span>
                </div>
                <?php if ($isBerjalan): ?>
                <a href="../checkout.php?order_db_id=<?= (int) $order['id'] ?>&studio_id=<?= (int) $order['studio_id'] ?>&tanggal=<?= urlencode($order['tanggal']) ?>&waktu=<?= urlencode($order['waktu']) ?>&addon_label=<?= urlencode($order['addon_label']) ?>&total=<?= (int) $order['total'] ?>" class="btn btn-outline btn-sm">Detail</a>
                <?php endif; ?>
            </div>
        </article>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted riwayat-empty">Belum ada transaksi<?= $filter !== 'all' ? ' dengan status ini' : '' ?>.</p>
    <?php endif; ?>
</div>

<?php
include $base_path . 'includes/pelanggan/layout-end.php';
include $base_path . 'includes/footer.php';
?>
