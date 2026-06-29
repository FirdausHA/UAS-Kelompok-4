<?php
session_start();

require_once '../config/database.php';
require_once '../models/Studio.php';
require_once '../includes/auth_guard.php';

requireAdmin();

$database = new Database();
$db = $database->getConnection();
$studioModel = new Studio($db);

if (!isset($_GET['action'])) {
    header('Location: ../views/admin/dashboard.php');
    exit();
}

$action = $_GET['action'];

if ($action === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nama' => trim($_POST['nama'] ?? ''),
        'deskripsi' => trim($_POST['deskripsi'] ?? ''),
        'gambar' => trim($_POST['gambar'] ?? ''),
        'harga' => (int) ($_POST['harga'] ?? 0),
        'luas_area' => trim($_POST['luas_area'] ?? ''),
        'rating' => (float) ($_POST['rating'] ?? 5.0),
        'is_populer' => isset($_POST['is_populer']) ? 1 : 0,
        'status' => ($_POST['status'] ?? 'available') === 'booked' ? 'booked' : 'available',
    ];

    if ($data['nama'] === '' || $data['gambar'] === '' || $data['harga'] <= 0) {
        header('Location: ../views/admin/dashboard.php?status=error');
        exit();
    }

    $studioModel->create($data);
    header('Location: ../views/admin/dashboard.php?status=created');
    exit();
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $data = [
        'nama' => trim($_POST['nama'] ?? ''),
        'deskripsi' => trim($_POST['deskripsi'] ?? ''),
        'gambar' => trim($_POST['gambar'] ?? ''),
        'harga' => (int) ($_POST['harga'] ?? 0),
        'luas_area' => trim($_POST['luas_area'] ?? ''),
        'rating' => (float) ($_POST['rating'] ?? 5.0),
        'is_populer' => isset($_POST['is_populer']) ? 1 : 0,
        'status' => ($_POST['status'] ?? 'available') === 'booked' ? 'booked' : 'available',
    ];

    if ($id <= 0 || $data['nama'] === '') {
        header('Location: ../views/admin/dashboard.php?status=error');
        exit();
    }

    $studioModel->update($id, $data);
    header('Location: ../views/admin/dashboard.php?status=updated');
    exit();
}

if ($action === 'hapus' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    if ($id > 0) {
        $studioModel->delete($id);
    }
    header('Location: ../views/admin/dashboard.php?status=deleted');
    exit();
}

header('Location: ../views/admin/dashboard.php');
exit();
