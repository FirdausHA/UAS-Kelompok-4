<?php

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . authBasePath() . 'views/auth/login.php');
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if (($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: ' . authBasePath() . 'index.php');
        exit();
    }
}

function requirePelanggan() {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'pelanggan') {
        $redirect = '';
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        if (strpos($script, 'checkout.php') !== false) {
            $redirect = 'checkout.php';
            if (!empty($_SERVER['QUERY_STRING'])) {
                $redirect .= '?' . $_SERVER['QUERY_STRING'];
            }
        } elseif (!empty($_SERVER['QUERY_STRING']) && strpos($script, 'studio-detail.php') !== false) {
            $redirect = 'studio-detail.php?' . $_SERVER['QUERY_STRING'];
        }
        $query = $redirect !== '' ? '?redirect=' . urlencode($redirect) : '';
        header('Location: ' . authBasePath() . 'views/auth/register.php' . $query);
        exit();
    }
}

function isPelanggan() {
    return isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'pelanggan';
}

function isAdmin() {
    return isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'admin';
}

function authBasePath() {
    static $base = null;
    if ($base !== null) return $base;

    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    if (strpos($script, '/views/admin/') !== false || strpos($script, '/views/auth/') !== false) {
        $base = '../../';
    } elseif (strpos($script, '/views/') !== false) {
        $base = '../';
    } elseif (strpos($script, '/controllers/') !== false) {
        $base = '../';
    } else {
        $base = '';
    }
    return $base;
}

function safeRedirectPath($path) {
    if ($path === '' || $path === null) return '';

    $path = urldecode($path);
    if (strpos($path, '://') !== false) return '';
    if (strpos($path, '..') !== false) return '';

    $allowed = ['checkout.php', 'studio-detail.php', 'views/checkout.php', 'views/studio-detail.php'];
    foreach ($allowed as $prefix) {
        if (strpos($path, $prefix) !== false) {
            return $path;
        }
    }
    return '';
}
