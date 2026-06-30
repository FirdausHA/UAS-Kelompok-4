<?php
session_start();

require_once '../../config/database.php';
require_once '../../models/Studio.php';
require_once '../../includes/auth_guard.php';
require_once '../../includes/helpers.php';

requireAdmin();

$database = new Database();
$db = $database->getConnection();
$studioModel = new Studio($db);

$id = (int) ($_GET['id'] ?? 0);
$studio = $studioModel->getById($id);

if (!$studio) {
    header('Location: studios.php');
    exit();
}

$page_title = 'Detail Studio: ' . htmlspecialchars($studio['nama']) . ' | Admin';
$admin_page = 'studios';

include '../../includes/admin/header.php';
?>

<header class="admin-topbar">
    <div>
        <a href="studios.php" class="btn-icon" title="Kembali">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="admin-page-title">Detail Studio</h1>
        <p class="admin-page-subtitle">Informasi lengkap studio.</p>
    </div>
</header>

<section class="admin-inventory">
    <div class="table-wrap">
        <div class="studio-detail">
            <div class="studio-detail-left">
                <div class="studio-detail-img-wrapper">
                    <img src="<?= htmlspecialchars(getStudioImageUrl($studio['gambar'])) ?>" alt="<?= htmlspecialchars($studio['nama']) ?>" class="studio-detail-img">
                </div>
                <div class="studio-detail-section">
                    <h3 class="studio-detail-section-title">Deskripsi</h3>
                    <div class="studio-detail-desc">
                        <?= nl2br(htmlspecialchars($studio['deskripsi'] ?: '-')) ?>
                    </div>
                </div>
            </div>
            <div class="studio-detail-info">
                <h2><?= htmlspecialchars($studio['nama']) ?></h2>
                
                <div class="studio-detail-field">
                    <div class="studio-detail-field-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div class="studio-detail-field-content">
                        <span class="studio-detail-field-label">Harga per Jam</span>
                        <span class="studio-detail-field-value">Rp <?= number_format((int)$studio['harga'], 0, ',', '.') ?></span>
                    </div>
                </div>

                <div class="studio-detail-field">
                    <div class="studio-detail-field-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div class="studio-detail-field-content">
                        <span class="studio-detail-field-label">Luas Area</span>
                        <span class="studio-detail-field-value"><?= htmlspecialchars($studio['luas_area'] ?: '-') ?></span>
                    </div>
                </div>

                <div class="studio-detail-field">
                    <div class="studio-detail-field-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                    <div class="studio-detail-field-content">
                        <span class="studio-detail-field-label">Rating</span>
                        <span class="studio-detail-field-value"><?= htmlspecialchars($studio['rating']) ?> / 5</span>
                    </div>
                </div>

                <div class="studio-detail-actions">
                    <a href="edit-studio.php?id=<?= (int)$studio['id'] ?>" class="btn btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Edit Studio
                    </a>
                    <a href="../../controllers/StudioController.php?action=delete&id=<?= (int)$studio['id'] ?>" class="btn btn-secondary" onclick="return confirm('Hapus studio ini?')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        Hapus Studio
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="admin-footer">
    <span>&copy; <?= date('Y') ?> Obsidian Studio. All rights reserved.</span>
    <div class="admin-footer-links">
        <a href="../../index.php#contact">Contact</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
    </div>
</footer>

<?php include '../../includes/admin/footer.php'; ?>
