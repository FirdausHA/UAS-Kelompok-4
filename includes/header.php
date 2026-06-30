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
                <div class="nav-avatar-wrapper" id="navAvatarWrapper">
                    <button class="nav-avatar-btn" id="navAvatarBtn" aria-label="Menu profil" aria-expanded="false" aria-haspopup="true">
                        <?= htmlspecialchars($nav_initials) ?>
                    </button>
                    <div class="nav-dropdown" id="navDropdown" role="menu" aria-hidden="true">
                        <div class="nav-dropdown-header">
                            <span class="nav-dropdown-name"><?= htmlspecialchars($nav_user_name) ?></span>
                            <span class="nav-dropdown-email"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></span>
                        </div>
                        <a href="<?= $base_path ?>views/pelanggan/profil.php" class="nav-dropdown-item" role="menuitem">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Profil
                        </a>
                        <a href="<?= $base_path ?>views/pelanggan/riwayat.php" class="nav-dropdown-item" role="menuitem">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>
                            Riwayat
                        </a>
                        <div class="nav-dropdown-divider"></div>
                        <a href="<?= $base_path ?>controllers/AuthController.php?action=logout" class="nav-dropdown-item nav-dropdown-logout" role="menuitem">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Logout
                        </a>
                    </div>
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

<script>
(function() {
    var btn = document.getElementById('navAvatarBtn');
    var dropdown = document.getElementById('navDropdown');
    var wrapper = document.getElementById('navAvatarWrapper');
    if (!btn || !dropdown) return;

    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        var isOpen = dropdown.classList.contains('open');
        dropdown.classList.toggle('open', !isOpen);
        btn.setAttribute('aria-expanded', String(!isOpen));
        dropdown.setAttribute('aria-hidden', String(isOpen));
    });

    document.addEventListener('click', function(e) {
        if (wrapper && !wrapper.contains(e.target)) {
            dropdown.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
            dropdown.setAttribute('aria-hidden', 'true');
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && dropdown.classList.contains('open')) {
            dropdown.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
            dropdown.setAttribute('aria-hidden', 'true');
            btn.focus();
        }
    });
})();
</script>
