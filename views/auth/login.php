<?php
session_start();
require_once '../../includes/auth_guard.php';
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
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Obsidian Studio</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .auth-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .auth-box { width: 100%; max-width: 400px; }
    </style>
</head>
<body class="auth-wrapper">
    <div class="auth-box">
        <div class="text-center mb-16">
            <h1 class="section-title">Masuk</h1>
            <p class="section-desc text-muted">Silakan masuk ke akun Obsidian Studio Anda.</p>
        </div>

        <div class="form-panel">
            <?php if ($status === 'success'): ?>
            <div class="alert alert-success">Registrasi berhasil! Silakan masuk.</div>
            <?php endif; ?>
            <?php if ($error === 'kredensial_salah'): ?>
            <div class="alert alert-error">Username/email atau password salah.</div>
            <?php endif; ?>

            <form action="../../controllers/AuthController.php?action=login" method="POST">
                <?php if ($redirect !== ''): ?>
                <input type="hidden" name="redirect" value="<?= $redirect ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label>Username atau Email</label>
                    <input type="text" name="username" required placeholder="Username atau email Anda">
                </div>
                <div class="form-group mb-16">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Masukkan password">
                </div>
                <button type="submit" class="btn btn-primary btn-full">Masuk</button>
            </form>

            <div class="text-center mt-20">
                <p class="form-hint">Belum punya akun?
                    <a href="register.php<?= $redirect !== '' ? '?redirect=' . urlencode($redirect) : '' ?>" class="text-primary">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
