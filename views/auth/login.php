<?php
session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../pelanggan/catalog.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Masuk - Obsidian Studio</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-box {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body class="auth-wrapper">
    <div class="auth-box">
        <div class="text-center mb-16">
            <h1 class="section-title">Masuk</h1>
            <p class="section-desc text-muted">Silakan masuk ke akun Obsidian Studio Anda.</p>
        </div>

        <div class="form-panel">
            <!-- Tampilkan pesan error jika login gagal -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="../../controllers/AuthController.php?action=login" method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="Masukkan username Anda">
                </div>
                
                <div class="form-group mb-16">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Masukkan password Anda">
                </div>
                
                <button type="submit" class="btn btn-primary btn-full">Masuk</button>
            </form>
            
            <div class="text-center mt-20">
                <p class="form-hint">Belum punya akun? <a href="register.php" class="text-primary">Daftar di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>