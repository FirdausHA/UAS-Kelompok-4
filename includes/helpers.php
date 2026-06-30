<?php

function formatRupiah($angka) {
    return 'Rp ' . number_format((int) $angka, 0, ',', '.');
}

function formatTanggalOrder($tanggal) {
    $bulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $ts = strtotime($tanggal);
    if (!$ts) return $tanggal;
    return date('j', $ts) . ' ' . strtoupper($bulan[(int) date('n', $ts)]) . ' ' . date('Y', $ts);
}

function formatTanggalIndoPanjang($tanggal) {
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $ts = strtotime($tanggal);
    if (!$ts) return $tanggal;
    return date('j', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
}

function orderStatusLabel($status) {
    $map = [
        'pending' => ['label' => 'Berjalan', 'class' => 'status-berjalan'],
        'confirmed' => ['label' => 'Berjalan', 'class' => 'status-berjalan'],
        'completed' => ['label' => 'Selesai', 'class' => 'status-selesai'],
        'cancelled' => ['label' => 'Batal', 'class' => 'status-batal'],
    ];
    return $map[$status] ?? ['label' => ucfirst($status), 'class' => 'status-pending'];
}

function orderStatusAdminLabel($status) {
    $map = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];
    return $map[$status] ?? $status;
}

function userInitials($name) {
    $parts = preg_split('/\s+/', trim($name));
    $initials = '';
    foreach (array_slice($parts, 0, 2) as $p) {
        $initials .= strtoupper(substr($p, 0, 1));
    }
    return $initials ?: 'U';
}
<<<<<<< HEAD
=======

function getBasePath() {
    $script = $_SERVER['SCRIPT_NAME'];
    $depth = substr_count($script, '/') - 1;
    $base = '';
    for ($i = 0; $i < $depth; $i++) {
        $base .= '../';
    }
    return $base;
}

function getStudioImageUrl($imagePath) {
    if (empty($imagePath)) {
        return '';
    }
    // If it's already a full URL, return it
    if (strpos($imagePath, 'http://') === 0 || strpos($imagePath, 'https://') === 0) {
        return $imagePath;
    }
    // Strip leading slashes
    $imagePath = ltrim($imagePath, '/\\');
    // Build absolute URL from project root folder
    // e.g. SCRIPT_NAME = /UAS-Kelompok-04/views/admin/studios.php
    // => projectRoot  = /UAS-Kelompok-04/
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $segments = explode('/', trim($scriptName, '/'));
    $projectRoot = (count($segments) > 1) ? '/' . $segments[0] . '/' : '/';
    return $projectRoot . $imagePath;
}

function getUserById($db, $user_id) {
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
