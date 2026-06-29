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
