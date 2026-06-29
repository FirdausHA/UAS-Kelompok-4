<?php
if (!isset($base_path)) {
    $base_path = '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Obsidian Studio' ?></title>
    <link rel="stylesheet" href="<?= $base_path ?>assets/css/style.css">
    <?php if (!empty($extra_css)): ?>
        <?php foreach ($extra_css as $css_file): ?>
    <link rel="stylesheet" href="<?= $base_path . $css_file ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body data-nav-mode="<?= htmlspecialchars($nav_mode ?? 'static') ?>" data-active-menu="<?= htmlspecialchars($active_menu ?? '') ?>">

<nav class="navbar">
    <div class="container">
        <a href="<?= $base_path ?>index.php" class="navbar-brand">Obsidian Studio</a>

        <button type="button" class="navbar-toggle" id="navToggle" aria-label="Buka menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <ul class="navbar-menu" id="navMenu">
            <li><a href="<?= $base_path ?>index.php#home" class="nav-link" data-menu="home" data-section="home">Home</a></li>
            <li><a href="<?= $base_path ?>views/katalog.php" class="nav-link" data-menu="catalog">Katalog</a></li>
            <li><a href="<?= $base_path ?>index.php#contact" class="nav-link" data-menu="contact" data-section="contact">Contact</a></li>
        </ul>

        <div class="navbar-actions">
            <?php if (isset($_SESSION['username']) && ($_SESSION['role'] ?? '') === 'admin'): ?>
                <a href="<?= $base_path ?>views/admin/dashboard.php" class="btn btn-secondary">Dashboard</a>
                <a href="<?= $base_path ?>controllers/AuthController.php?action=logout" class="btn btn-secondary">Logout</a>
            <?php elseif (isset($_SESSION['username']) && ($_SESSION['role'] ?? '') === 'pelanggan'): ?>
                <?php
                $nav_user_name = $_SESSION['nama_lengkap'] ?? $_SESSION['username'];
                if (!function_exists('userInitials') && file_exists(__DIR__ . '/helpers.php')) {
                    require_once __DIR__ . '/helpers.php';
                }
                $nav_initials = function_exists('userInitials') ? userInitials($nav_user_name) : strtoupper(substr($nav_user_name, 0, 1));
                ?>
                <div class="navbar-user-menu">
                    <div class="navbar-user-links">
                        <a href="<?= $base_path ?>views/pelanggan/profil.php">Profil</a>
                        <a href="<?= $base_path ?>views/pelanggan/riwayat.php">Riwayat</a>
                    </div>
                    <a href="<?= $base_path ?>views/pelanggan/profil.php" class="nav-avatar" title="Profil Saya"><?= htmlspecialchars($nav_initials) ?></a>
                    <a href="<?= $base_path ?>controllers/AuthController.php?action=logout" class="btn btn-secondary">Logout</a>
                </div>
            <?php elseif (isset($_SESSION['username'])): ?>
                <span class="text-muted">Halo, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="<?= $base_path ?>controllers/AuthController.php?action=logout" class="btn btn-secondary">Logout</a>
            <?php else: ?>
                <a href="<?= $base_path ?>views/auth/login.php" class="btn btn-primary">Login/Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>