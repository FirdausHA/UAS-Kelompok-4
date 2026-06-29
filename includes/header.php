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
</head>
<body>

<nav class="navbar">
    <div class="container">
        <a href="<?= $base_path ?>index.php" class="navbar-brand">Obsidian Studio</a>

        <ul class="navbar-menu">
            <li><a href="<?= $base_path ?>index.php" class="<?= ($active_menu ?? '') == 'home' ? 'active' : '' ?>">Home</a></li>
            <li><a href="<?= $base_path ?>index.php#katalog" class="<?= ($active_menu ?? '') == 'catalog' ? 'active' : '' ?>">Catalog</a></li>
            <li><a href="<?= $base_path ?>views/contact.php" class="<?= ($active_menu ?? '') == 'contact' ? 'active' : '' ?>">Contact</a></li>
        </ul>

        <div class="navbar-actions">
            <?php if (isset($_SESSION['username'])): ?>
                <span class="text-muted">Halo, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="<?= $base_path ?>controllers/AuthController.php?action=logout" class="btn btn-secondary">Logout</a>
            <?php else: ?>
                <a href="<?= $base_path ?>views/auth/login.php" class="btn btn-primary">Login/Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>