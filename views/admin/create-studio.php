<?php
session_start();

require_once '../../config/database.php';
require_once '../../models/Studio.php';
require_once '../../includes/auth_guard.php';

requireAdmin();

$page_title = 'Tambah Studio Baru | Admin';
$admin_page = 'studios';
$status = $_GET['status'] ?? '';

include '../../includes/admin/header.php';
?>

<header class="admin-topbar">
    <div>
        <a href="studios.php" class="btn-icon" title="Kembali">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="admin-page-title">Tambah Studio Baru</h1>
        <p class="admin-page-subtitle">Isi detail studio baru di bawah ini.</p>
    </div>
</header>

<?php if ($status === 'error'): ?>
<div class="alert alert-error admin-alert">Gagal menyimpan studio. Pastikan semua data diisi dengan benar.</div>
<?php endif; ?>

<section class="admin-inventory">
    <div class="table-wrap">
        <form action="../../controllers/StudioController.php?action=simpan" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="studioNama">Nama Studio</label>
                <input type="text" id="studioNama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="studioGambar">Upload Gambar Studio</label>
                <input type="file" id="studioGambar" name="gambar" accept="image/*" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="studioHarga">Harga / Jam (Rp)</label>
                    <input type="number" id="studioHarga" name="harga" min="0" required>
                </div>
                <div class="form-group">
                    <label for="studioLuas">Luas Area</label>
                    <input type="text" id="studioLuas" name="luas_area" placeholder="50m² Area">
                </div>
            </div>
            <div class="form-group">
                <label for="studioRating">Rating</label>
                <input type="number" id="studioRating" name="rating" step="0.1" min="0" max="5" value="5.0">
            </div>
            <div class="form-group">
                <label for="studioDeskripsi">Deskripsi</label>
                <textarea id="studioDeskripsi" name="deskripsi" rows="5"></textarea>
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
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Simpan Studio
                </button>
            </div>
        </form>
    </div>
</section>

<?php include '../../includes/admin/footer.php'; ?>