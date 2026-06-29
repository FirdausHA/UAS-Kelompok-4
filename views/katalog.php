<?php
session_start();

$base_path = '../';

require_once $base_path . 'config/database.php';
require_once $base_path . 'models/Studio.php';

$database = new Database();
$db = $database->getConnection();
$studioModel = new Studio($db);
$daftarStudio = $studioModel->getAll();

$page_title = 'Katalog Studio | Obsidian Studio';
$active_menu = 'catalog';
$nav_mode = 'static';
$detail_url_mode = 'relative';

function formatHarga($angka) {
    if ($angka >= 1000) {
        return 'Rp ' . number_format($angka / 1000, 0, ',', '.') . 'k';
    }
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

include $base_path . 'includes/header.php';
?>

<section class="section section-katalog page-katalog">
    <div class="container">
        <div class="section-header animate-on-load" data-animate="fade-up">
            <div>
                <h1 class="section-title">Katalog Studio</h1>
                <p class="section-desc">Jelajahi seluruh koleksi ruang kreatif kami. Pilih studio dengan tema unik untuk kebutuhan produksi Anda.</p>
            </div>
            <div class="section-controls">
                <button type="button" class="control-btn" aria-label="Filter studio" title="Filter">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                </button>
                <button type="button" class="control-btn" aria-label="Tampilan grid" title="Grid">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                </button>
            </div>
        </div>

        <?php include $base_path . 'includes/partials/studio-grid.php'; ?>

        <div class="katalog-page-meta animate-on-load" data-animate="fade-up" data-delay="150">
            <p class="text-muted">Menampilkan <?= count($daftarStudio) ?> studio tersedia</p>
            <a href="<?= $base_path ?>index.php#katalog" class="link-arrow">&larr; Kembali ke Beranda</a>
        </div>
    </div>
</section>

<?php include $base_path . 'includes/footer.php'; ?>
