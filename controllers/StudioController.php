<?php
session_start();

require_once '../config/database.php';
require_once '../models/Studio.php';
require_once '../includes/auth_guard.php';

requireAdmin();

$database = new Database();
$db = $database->getConnection();
$studioModel = new Studio($db);

<<<<<<< HEAD
=======
function handleImageUpload($fieldName, $existingImage = null, $isRequired = false) {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        if ($isRequired && !$existingImage) {
            return null;
        }
        return $existingImage;
    }

    $file = $_FILES[$fieldName];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowedTypes)) {
        return $existingImage;
    }

    if ($file['size'] > $maxSize) {
        return $existingImage;
    }

    $uploadDir = __DIR__ . '/../uploads/studio/';
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = 'studio_' . time() . '_' . uniqid() . '.' . $extension;
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Delete old image if exists
        if ($existingImage && strpos($existingImage, 'uploads/studio/') !== false) {
            $oldPath = __DIR__ . '/../' . $existingImage;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
        return 'uploads/studio/' . $fileName;
    }

    return $existingImage;
}

>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
if (!isset($_GET['action'])) {
    header('Location: ../views/admin/dashboard.php');
    exit();
}

$action = $_GET['action'];

if ($action === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
<<<<<<< HEAD
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
=======
    $gambar = handleImageUpload('gambar', null, true);
    $data = [
        'nama' => trim($_POST['nama'] ?? ''),
        'deskripsi' => trim($_POST['deskripsi'] ?? ''),
        'gambar' => $gambar,
        'harga' => (int) ($_POST['harga'] ?? 0),
        'luas_area' => trim($_POST['luas_area'] ?? ''),
        'rating' => (float) ($_POST['rating'] ?? 5.0),
        'is_populer' => 0
    ];

    if ($data['nama'] === '' || $data['gambar'] === null || $data['harga'] <= 0) {
        header('Location: ../views/admin/create-studio.php?status=error');
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
        exit();
    }

    $studioModel->create($data);
<<<<<<< HEAD
    header('Location: ../views/admin/dashboard.php?status=created');
=======
    header('Location: ../views/admin/studios.php?status=created');
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
    exit();
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
<<<<<<< HEAD
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
=======
    $studio = $studioModel->getById($id);
    $gambar = handleImageUpload('gambar', $studio['gambar'] ?? null);
    $data = [
        'nama' => trim($_POST['nama'] ?? ''),
        'deskripsi' => trim($_POST['deskripsi'] ?? ''),
        'gambar' => $gambar ?: ($studio['gambar'] ?? ''),
        'harga' => (int) ($_POST['harga'] ?? 0),
        'luas_area' => trim($_POST['luas_area'] ?? ''),
        'rating' => (float) ($_POST['rating'] ?? 5.0),
        'is_populer' => $studio['is_populer'] ?? 0
    ];

    if ($id <= 0 || $data['nama'] === '') {
        header('Location: ../views/admin/edit-studio.php?id=' . $id . '&status=error');
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
        exit();
    }

    $studioModel->update($id, $data);
<<<<<<< HEAD
    header('Location: ../views/admin/dashboard.php?status=updated');
=======
    header('Location: ../views/admin/studios.php?status=updated');
    exit();
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    if ($id > 0) {
        $studio = $studioModel->getById($id);
        $studioModel->delete($id);
        // Delete image file
        if ($studio && $studio['gambar'] && strpos($studio['gambar'], 'uploads/studio/') !== false) {
            $oldPath = __DIR__ . '/../' . $studio['gambar'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
    }
    header('Location: ../views/admin/studios.php?status=deleted');
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
    exit();
}

if ($action === 'hapus' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    if ($id > 0) {
<<<<<<< HEAD
        $studioModel->delete($id);
    }
    header('Location: ../views/admin/dashboard.php?status=deleted');
=======
        $studio = $studioModel->getById($id);
        $studioModel->delete($id);
        // Delete image file
        if ($studio && $studio['gambar'] && strpos($studio['gambar'], 'uploads/studio/') !== false) {
            $oldPath = __DIR__ . '/../' . $studio['gambar'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
    }
    header('Location: ../views/admin/studios.php?status=deleted');
>>>>>>> cac3d16ec6ccf1868c6d3ae6a9ea2567a7f69b0a
    exit();
}

header('Location: ../views/admin/dashboard.php');
exit();
