<?php
session_start();

$base_path = '';

require_once 'config/database.php';
require_once 'models/Studio.php';

$database = new Database();
$db = $database->getConnection();
$studioModel = new Studio($db);
$daftarStudio = $studioModel->getAll();

$page_title = 'Obsidian Studio | Premium Creative Spaces';
$active_menu = 'home';

// format harga biar tampil Rp 450k gitu
function formatHarga($angka) {
    if ($angka >= 1000) {
        return 'Rp ' . number_format($angka / 1000, 0, ',', '.') . 'k';
    }
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-bg" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDkyIuPzi4mQ9NbMN1P2CCOsRlQ_yAFCiONelG5msQAS5xtHXs2SLPEVtyU08MamdXcB2NqTckr00XwCi_7F2LjcpMFNyfn1s1838Mo-Jfe-zt8FdtPefEzYwuvXTDyu6iOrlg6YN71k8qT0XXiIcdaTrp1ptWCMh1rmDLJjdXYIDwqB2NojvR9t3Ada0IBDp5rdjqow3e5P8s8Hi_T1bn56Yr2rwnPRprGCSjHcPa_0JKtfRjaqRTj9OFfvMV57mDufOzQZIocBDg');"></div>
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <span class="hero-label">Eksklusif &amp; Profesional</span>
        <h1>Abadikan Momen Terbaik Anda di <span>Studio Profesional</span> Kami.</h1>
        <p>Ruang kreatif yang dirancang untuk presisi. Temukan studio dengan peralatan terbaik dan atmosfer sinematik untuk hasil karya luar biasa.</p>
        <div class="hero-buttons">
            <a href="#katalog" class="btn btn-primary">Jelajahi Studio</a>
            <a href="#katalog" class="btn btn-secondary">Lihat Katalog</a>
        </div>
    </div>
</section>

<!-- Katalog Studio -->
<section class="section" id="katalog">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Katalog Studio</h2>
                <p class="section-desc">Pilih dari berbagai pilihan ruang dengan tema unik yang telah dikurasi khusus untuk kebutuhan produksi Anda.</p>
            </div>
        </div>

        <?php if (count($daftarStudio) > 0): ?>
        <div class="studio-grid">
            <?php foreach ($daftarStudio as $studio): ?>
            <div class="card">
                <div class="card-img-wrap">
                    <img
                        src="<?= htmlspecialchars($studio['gambar']) ?>"
                        alt="<?= htmlspecialchars($studio['nama']) ?>"
                        class="card-img"
                    >
                    <?php if (!empty($studio['is_populer'])): ?>
                    <span class="card-badge">Populer</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h3 class="card-title"><?= htmlspecialchars($studio['nama']) ?></h3>
                    <div class="card-meta">
                        <?php if (!empty($studio['rating'])): ?>
                        <span class="rating">&#9733; <?= htmlspecialchars($studio['rating']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($studio['luas_area'])): ?>
                        <span><?= htmlspecialchars($studio['luas_area']) ?></span>
                        <?php endif; ?>
                    </div>
                    <p class="text-muted"><?= htmlspecialchars($studio['deskripsi']) ?></p>
                    <div class="card-footer">
                        <div>
                            <span class="card-price-label">Mulai Dari</span>
                            <span class="card-price"><?= formatHarga($studio['harga']) ?><small>/jam</small></span>
                        </div>
                        <a href="#" class="btn btn-outline">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-muted text-center">Belum ada studio tersedia. Data akan muncul setelah admin menambahkan studio.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
