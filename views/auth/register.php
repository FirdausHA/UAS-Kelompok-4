<?php
session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: ../admin/dashboard.php');
    } else {
        header('Location: ../../index.php');
    }
    exit();
}

$redirect = isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : '';
$status = $_GET['status'] ?? '';
$fromCheckout = strpos($redirect, 'checkout') !== false;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Obsidian Studio</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .auth-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .auth-box { width: 100%; max-width: 500px; }
    </style>
</head>
<body class="auth-wrapper">
    <div class="auth-box">
        <div class="text-center mb-16">
            <h1 class="section-title">Daftar Akun</h1>
            <p class="section-desc text-muted">
                <?php if ($fromCheckout): ?>
                Buat akun pelanggan terlebih dahulu untuk melanjutkan ke pembayaran.
                <?php else: ?>
                Bergabunglah dengan Obsidian Studio hari ini.
                <?php endif; ?>
            </p>
        </div>

        <div class="form-panel">
            <?php if ($status === 'failed'): ?>
            <div class="alert alert-error">Registrasi gagal. Periksa kembali data Anda.</div>
            <?php elseif ($status === 'password_mismatch'): ?>
            <div class="alert alert-error">Konfirmasi password tidak cocok.</div>
            <?php elseif ($status === 'username_exists'): ?>
            <div class="alert alert-error">Username sudah digunakan.</div>
            <?php elseif ($status === 'email_exists'): ?>
            <div class="alert alert-error">Email sudah terdaftar.</div>
            <?php endif; ?>

            <form action="../../controllers/AuthController.php?action=register" method="POST">
                <?php if ($redirect !== ''): ?>
                <input type="hidden" name="redirect" value="<?= $redirect ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required placeholder="Nama lengkap Anda">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required placeholder="Pilih username">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required placeholder="email@contoh.com">
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Buat password baru">
                </div>
                <div class="form-group mb-16">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="confirm_password" required placeholder="Ketik ulang password">
                </div>
                <button type="submit" class="btn btn-primary btn-full">Daftar Sekarang</button>
            </form>

            <div class="text-center mt-20">
                <p class="form-hint">Sudah punya akun?
                    <a href="login.php<?= $redirect !== '' ? '?redirect=' . urlencode($redirect) : '' ?>" class="text-primary">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
