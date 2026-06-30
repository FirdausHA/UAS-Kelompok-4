<?php
session_start();

$base_path = '../../';

require_once $base_path . 'config/database.php';
require_once $base_path . 'models/User.php';
require_once $base_path . 'includes/auth_guard.php';
require_once $base_path . 'includes/helpers.php';

requirePelanggan();

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);
$user = $userModel->getById((int) $_SESSION['user_id']);

if (!$user) {
    header('Location: ' . $base_path . 'controllers/AuthController.php?action=logout');
    exit();
}

$page_title = 'Profil Saya | Obsidian Studio';
$active_menu = '';
$nav_mode = 'static';
$extra_css = ['assets/css/pelanggan.css'];
$pelanggan_page = 'profil';
$status = $_GET['status'] ?? '';

include $base_path . 'includes/header.php';
include $base_path . 'includes/pelanggan/layout-start.php';
?>

<?php if ($status === 'updated'): ?>
<div class="alert alert-success">Profil berhasil diperbarui.</div>
<?php elseif ($status === 'password_updated'): ?>
<div class="alert alert-success">Password berhasil diubah.</div>
<?php elseif ($status === 'password_error'): ?>
<div class="alert alert-error">Password tidak cocok atau terlalu pendek (min. 6 karakter).</div>
<?php elseif ($status === 'error'): ?>
<div class="alert alert-error">Gagal memperbarui profil.</div>
<?php endif; ?>

<section class="profile-main">
    <div class="profile-section">
        <h2 class="profile-section-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Informasi Akun
        </h2>

        <form action="<?= $base_path ?>controllers/ProfileController.php?action=update_profil" method="POST" class="profile-form-grid">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" name="no_telepon" value="<?= htmlspecialchars($user['no_telepon'] ?? '') ?>" placeholder="+62 812 3456 7890">
            </div>
            <div class="form-group">
                <label>Bergabung Sejak</label>
                <input type="text" value="<?= formatTanggalIndoPanjang($user['created_at']) ?>" disabled>
            </div>
            <div class="form-group form-full">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" rows="4" placeholder="Alamat lengkap Anda"><?= htmlspecialchars($user['alamat'] ?? '') ?></textarea>
            </div>
            <div class="form-full">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <div class="profile-section">
        <h2 class="profile-section-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Ganti Password
        </h2>
        <form action="<?= $base_path ?>controllers/ProfileController.php?action=update_password" method="POST" class="profile-password-form">
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password" required minlength="6">
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="confirm_password" required minlength="6">
            </div>
            <button type="submit" class="btn btn-secondary">Ganti Password</button>
        </form>
    </div>
</section>

<?php
include $base_path . 'includes/pelanggan/layout-end.php';
include $base_path . 'includes/footer.php';
?>
