<?php
if (!isset($admin_page)) {
    $admin_page = 'dashboard';
}
$admin_name = $_SESSION['nama_lengkap'] ?? $_SESSION['username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Admin | Obsidian Studio' ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body class="admin-body">

<div class="admin-layout">
    <aside class="admin-sidebar">
        <a href="dashboard.php" class="admin-brand">Obsidian Studio</a>

        <div class="admin-profile">
            <div class="admin-avatar"><?= strtoupper(substr($admin_name, 0, 1)) ?></div>
            <div>
                <p class="admin-profile-name">Admin Panel</p>
                <p class="admin-profile-role">Management Suite</p>
            </div>
        </div>

        <nav class="admin-nav">
            <a href="dashboard.php" class="admin-nav-link<?= $admin_page === 'dashboard' ? ' is-active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="dashboard.php#inventory" class="admin-nav-link<?= $admin_page === 'studios' ? ' is-active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                Studio Management
            </a>
            <a href="orders.php" class="admin-nav-link<?= $admin_page === 'orders' ? ' is-active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Order Management
            </a>
            <a href="users.php" class="admin-nav-link<?= $admin_page === 'users' ? ' is-active' : '' ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                User Management
            </a>
        </nav>

        <a href="../../controllers/AuthController.php?action=logout" class="admin-logout">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Logout
        </a>
    </aside>

    <div class="admin-main">
