<?php
session_start();

$base_path = '../../';

require_once $base_path . 'config/database.php';
require_once $base_path . 'models/Order.php';
require_once $base_path . 'models/Studio.php';
require_once $base_path . 'includes/auth_guard.php';
require_once $base_path . 'includes/helpers.php';

requirePelanggan();

$order_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($order_id <= 0) {
    header('Location: riwayat.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$orderModel = new Order($db);
$studioModel = new Studio($db);

$order = $orderModel->getById($order_id);

if (!$order || (int)$order['user_id'] !== (int)$_SESSION['user_id']) {
    header('Location: riwayat.php');
    exit();
}

$studio = $studioModel->getById($order['studio_id']);

$page_title = 'Detail Order | Obsidian Studio';
$extra_css = ['assets/css/admin.css'];
$pelanggan_page = 'riwayat';

include $base_path . 'includes/header.php';
include $base_path . 'includes/pelanggan/layout-start.php';

$status_info = orderStatusLabel($order['status']);
?>

<style>
.detail-order-wrap {
    max-width: 860px;
    margin: 0 auto;
}
.detail-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #e8e1dc;
    background: rgba(255,255,255,0.05);
    border: 1px solid #2a2220;
    text-decoration: none;
    transition: background 0.2s, border-color 0.2s;
    margin-bottom: 28px;
}
.detail-back-btn:hover {
    background: rgba(255,255,255,0.09);
    border-color: #a3492f;
    color: #fff;
}
.detail-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
    gap: 16px;
}
.detail-title-group h1 {
    font-size: 1.875rem;
    font-weight: 700;
    margin: 0 0 6px;
    letter-spacing: -0.02em;
}
.detail-order-code {
    font-size: 0.875rem;
    color: #948e89;
    margin: 0;
}
.detail-status-pill {
    padding: 8px 20px;
    border-radius: 9999px;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    flex-shrink: 0;
}
.detail-card {
    background: #1a1614;
    border: 1px solid #2a2220;
    border-radius: 14px;
    overflow: hidden;
}
.detail-studio-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px 28px;
    border-bottom: 1px solid #2a2220;
}
.detail-studio-img {
    width: 72px;
    height: 56px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #2a2220;
    flex-shrink: 0;
}
.detail-studio-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #e8e1dc;
    margin: 0 0 4px;
}
.detail-studio-desc {
    font-size: 0.85rem;
    color: #948e89;
    margin: 0;
    line-height: 1.5;
}
.detail-body {
    padding: 24px 28px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
@media (max-width: 640px) {
    .detail-body { grid-template-columns: 1fr; }
    .detail-header { flex-direction: column; align-items: flex-start; }
}
.detail-info-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    background: rgba(255,255,255,0.02);
    border: 1px solid #2a2220;
    border-radius: 10px;
}
.detail-info-icon {
    width: 44px;
    height: 44px;
    flex-shrink: 0;
    background: rgba(163, 73, 47, 0.12);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #a3492f;
}
.detail-info-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #948e89;
    margin: 0 0 4px;
    font-weight: 600;
}
.detail-info-value {
    font-size: 0.975rem;
    font-weight: 600;
    color: #e8e1dc;
    margin: 0;
}
.detail-info-value.highlight {
    font-size: 1.1rem;
    color: #d07548;
}
.detail-footer {
    padding: 20px 28px;
    border-top: 1px solid #2a2220;
    display: flex;
    align-items: center;
    gap: 12px;
}
.order-status.status-berjalan {
    background: rgba(78, 170, 238, 0.15);
    color: #4eaaee;
    border: 1px solid rgba(78, 170, 238, 0.3);
}
.order-status.status-selesai {
    background: rgba(111, 207, 151, 0.15);
    color: #6fcf97;
    border: 1px solid rgba(111, 207, 151, 0.3);
}
.order-status.status-batal {
    background: rgba(224, 84, 113, 0.15);
    color: #e05471;
    border: 1px solid rgba(224, 84, 113, 0.3);
}
.order-status.status-pending {
    background: rgba(232, 184, 75, 0.15);
    color: #e8b84b;
    border: 1px solid rgba(232, 184, 75, 0.3);
}
</style>

<div class="detail-order-wrap">

    <a href="riwayat.php" class="detail-back-btn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali ke Riwayat
    </a>

    <div class="detail-header">
        <div class="detail-title-group">
            <h1>Detail Order</h1>
            <p class="detail-order-code">Order #<?= htmlspecialchars($order['order_code']) ?></p>
        </div>
        <span class="detail-status-pill order-status <?= $status_info['class'] ?>">
            <?= strtoupper($status_info['label']) ?>
        </span>
    </div>

    <div class="detail-card">
        <!-- Studio Header -->
        <div class="detail-studio-header">
            <img
                src="<?= htmlspecialchars(getStudioImageUrl($studio['gambar'])) ?>"
                alt="<?= htmlspecialchars($studio['nama']) ?>"
                class="detail-studio-img"
            >
            <div>
                <p class="detail-studio-name"><?= htmlspecialchars($studio['nama']) ?></p>
                <p class="detail-studio-desc"><?= htmlspecialchars($studio['deskripsi'] ?: '-') ?></p>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="detail-body">
            <!-- Tanggal Booking -->
            <div class="detail-info-item">
                <div class="detail-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <div>
                    <p class="detail-info-label">Tanggal Booking</p>
                    <p class="detail-info-value"><?= htmlspecialchars(formatTanggalIndoPanjang($order['tanggal'])) ?></p>
                </div>
            </div>

            <!-- Waktu -->
            <div class="detail-info-item">
                <div class="detail-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>
                    </svg>
                </div>
                <div>
                    <p class="detail-info-label">Waktu</p>
                    <p class="detail-info-value"><?= htmlspecialchars(str_replace('-', ' - ', $order['waktu'])) ?></p>
                </div>
            </div>

            <!-- Paket -->
            <div class="detail-info-item">
                <div class="detail-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                </div>
                <div>
                    <p class="detail-info-label">Paket</p>
                    <p class="detail-info-value"><?= htmlspecialchars($order['addon_label']) ?></p>
                </div>
            </div>

            <!-- Total Harga -->
            <div class="detail-info-item">
                <div class="detail-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <div>
                    <p class="detail-info-label">Total Harga</p>
                    <p class="detail-info-value highlight"><?= formatRupiah($order['total']) ?></p>
                </div>
            </div>

            <!-- Metode Pembayaran -->
            <div class="detail-info-item">
                <div class="detail-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                </div>
                <div>
                    <p class="detail-info-label">Metode Pembayaran</p>
                    <p class="detail-info-value"><?= htmlspecialchars(ucfirst($order['payment_method'])) ?></p>
                </div>
            </div>

            <!-- Bukti Pembayaran (jika ada) -->
            <?php if ($order['bukti_file']): ?>
            <div class="detail-info-item">
                <div class="detail-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/>
                    </svg>
                </div>
                <div>
                    <p class="detail-info-label">Bukti Pembayaran</p>
                    <a href="<?= getBasePath() ?>uploads/bukti/<?= rawurlencode($order['bukti_file']) ?>" target="_blank" style="font-size:0.975rem; font-weight:600; color:#a3492f; text-decoration:underline;">Lihat Bukti</a>
                </div>
            </div>
            <?php endif; ?>
        </div>


    </div>

</div>

<?php
include $base_path . 'includes/pelanggan/layout-end.php';
include $base_path . 'includes/footer.php';
?>
