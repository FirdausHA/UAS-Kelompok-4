<?php
session_start();
if (isset($_SESSION['role'])) {
    header("Location: ../../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - Obsidian Studio</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .auth-box {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body class="auth-wrapper">
    <div class="auth-box">
        <div class="text-center mb-16">
            <h1 class="section-title">Daftar Akun</h1>
            <p class="section-desc text-muted">Bergabunglah dengan Obsidian Studio hari ini.</p>
        </div>

        <div class="form-panel">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="../../controllers/AuthController.php?action=register" method="POST">
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
                        <input type="email" name="email" required placeholder="Alamat email aktif">
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
                <p class="form-hint">Sudah punya akun? <a href="login.php" class="text-primary">Masuk di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>