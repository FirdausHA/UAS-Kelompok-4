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
$daftarStudio = $studioModel->getAll();
$page_title = 'Studio Inventory | Admin';
$admin_page = 'studios';
$status = $_GET['status'] ?? '';

include '../../includes/admin/header.php';
?>

<header class="admin-topbar">
    <div>
        <h1 class="admin-page-title">Studio Inventory</h1>
        <p class="admin-page-subtitle">Kelola semua studio yang tersedia.</p>
    </div>
    <a href="create-studio.php" class="btn btn-primary" style="background: #c0561e; border-color: #c0561e; color: #fff; font-weight: 700; padding: 12px 24px; font-size: 0.9rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Tambah Studio Baru
    </a>
</header>

<?php if ($status === 'created'): ?>
<div class="alert alert-success admin-alert">Studio baru berhasil ditambahkan.</div>
<?php elseif ($status === 'updated'): ?>
<div class="alert alert-success admin-alert">Data studio berhasil diperbarui.</div>
<?php elseif ($status === 'deleted'): ?>
<div class="alert alert-success admin-alert">Studio berhasil dihapus.</div>
<?php elseif ($status === 'error'): ?>
<div class="alert alert-error admin-alert">Gagal memproses data studio.</div>
<?php endif; ?>

<section class="admin-inventory" id="inventory">
    <div class="table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>PHOTO</th>
                    <th>STUDIO NAME</th>
                    <th>PRICE / HOUR</th>
                    <th>DESCRIPTION</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftarStudio as $i => $studio): ?>
                <tr>
                    <td class="td-no"><span><?= $i + 1 ?></span></td>
                    <td><img src="<?= htmlspecialchars(getStudioImageUrl($studio['gambar'])) ?>" alt="<?= htmlspecialchars($studio['nama']) ?>" class="table-thumb"></td>
                    <td><span class="table-studio-name"><?= htmlspecialchars($studio['nama']) ?></span></td>
                    <td><strong class="table-price"><?= formatRupiah($studio['harga']) ?></strong></td>
                    <td><span class="table-desc"><?= htmlspecialchars(substr($studio['deskripsi'], 0, 55)) ?><?= strlen($studio['deskripsi']) > 55 ? '...' : '' ?></span></td>
                    <td><span class="status-badge status-available">Available</span></td>
                    <td>
                        <div class="table-actions">
                            <a href="detail-studio.php?id=<?= $studio['id'] ?>" class="btn-icon btn-detail" title="Lihat Detail">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            <a href="edit-studio.php?id=<?= $studio['id'] ?>" class="btn-icon btn-edit" title="Edit Studio">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </a>
                            <a href="../../controllers/StudioController.php?action=delete&id=<?= $studio['id'] ?>" class="btn-icon btn-delete" title="Hapus Studio" onclick="return confirm('Anda yakin ingin menghapus studio ini?');">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p class="table-footer">Showing <?= count($daftarStudio) ?> of <?= count($daftarStudio) ?> studios</p>
</section>

<?php include '../../includes/admin/footer.php'; ?>