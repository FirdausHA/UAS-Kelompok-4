<?php
session_start();

require_once '../config/database.php';
require_once '../models/User.php';
require_once '../includes/auth_guard.php';

requirePelanggan();

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

if (!isset($_GET['action'])) {
    header('Location: ../views/pelanggan/profil.php');
    exit();
}

$action = $_GET['action'];
$userId = (int) $_SESSION['user_id'];

if ($action === 'update_profil' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_lengkap'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telepon = trim($_POST['no_telepon'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    if ($nama === '' || $email === '') {
        header('Location: ../views/pelanggan/profil.php?status=error');
        exit();
    }

    $userModel->updateProfile($userId, $nama, $email, $telepon, $alamat);
    $_SESSION['nama_lengkap'] = $nama;
    $_SESSION['email'] = $email;

    header('Location: ../views/pelanggan/profil.php?status=updated');
    exit();
}

if ($action === 'update_password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (strlen($password) < 6 || $password !== $confirm) {
        header('Location: ../views/pelanggan/profil.php?status=password_error');
        exit();
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $userModel->updatePassword($userId, $hash);

    header('Location: ../views/pelanggan/profil.php?status=password_updated');
    exit();
}

header('Location: ../views/pelanggan/profil.php');
exit();
