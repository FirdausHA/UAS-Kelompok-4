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

$page_title = 'Edit Studio: ' . htmlspecialchars($studio['nama']) . ' | Admin';
$admin_page = 'studios';
$status = $_GET['status'] ?? '';

include '../../includes/admin/header.php';
?>

<header class="admin-topbar">
    <div>
        <a href="studios.php" class="btn-icon" title="Kembali">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="admin-page-title">Edit Studio</h1>
        <p class="admin-page-subtitle">Update detail studio di bawah ini.</p>
    </div>
</header>

<?php if ($status === 'error'): ?>
<div class="alert alert-error admin-alert">Gagal menyimpan studio. Pastikan semua data diisi dengan benar.</div>
<?php endif; ?>

<section class="admin-inventory">
    <div class="table-wrap">
        <form action="../../controllers/StudioController.php?action=update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= (int)$studio['id'] ?>">
            <div class="form-group">
                <label for="studioNama">Nama Studio</label>
                <input type="text" id="studioNama" name="nama" value="<?= htmlspecialchars($studio['nama']) ?>" required>
            </div>
            <div class="form-group">
                <label>Gambar Saat Ini</label>
                <img src="<?= htmlspecialchars(getStudioImageUrl($studio['gambar'])) ?>" alt="" class="table-thumb" style="width: 120px; height: auto;">
            </div>
            <div class="form-group">
                <label for="studioGambar">Upload Gambar Baru (Opsional)</label>
                <input type="file" id="studioGambar" name="gambar" accept="image/*">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="studioHarga">Harga / Jam (Rp)</label>
                    <input type="number" id="studioHarga" name="harga" min="0" value="<?= htmlspecialchars($studio['harga']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="studioLuas">Luas Area</label>
                    <input type="text" id="studioLuas" name="luas_area" value="<?= htmlspecialchars($studio['luas_area']) ?>" placeholder="50m² Area">
                </div>
            </div>
            <div class="form-group">
                <label for="studioRating">Rating</label>
                <input type="number" id="studioRating" name="rating" step="0.1" min="0" max="5" value="<?= htmlspecialchars($studio['rating']) ?>">
            </div>
            <div class="form-group">
                <label for="studioDeskripsi">Deskripsi</label>
                <textarea id="studioDeskripsi" name="deskripsi" rows="5"><?= htmlspecialchars($studio['deskripsi']) ?></textarea>
            </div>
            <div class="form-row">
                <a href="studios.php" class="btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Update Studio
                </button>
            </div>
        </form>
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
