<?php
session_start();

require_once '../config/database.php';
require_once '../models/User.php';
require_once '../includes/auth_guard.php';

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

if (!isset($_GET['action'])) {
    header('Location: ../index.php');
    exit();
}

$action = $_GET['action'];

if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_lengkap'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $redirect = safeRedirectPath($_POST['redirect'] ?? '');

    if ($nama === '' || $username === '' || $email === '' || $password === '') {
        header('Location: ../views/auth/register.php?status=failed&redirect=' . urlencode($redirect));
        exit();
    }

    if ($password !== $confirm) {
        header('Location: ../views/auth/register.php?status=password_mismatch&redirect=' . urlencode($redirect));
        exit();
    }

    if ($userModel->usernameExists($username)) {
        header('Location: ../views/auth/register.php?status=username_exists&redirect=' . urlencode($redirect));
        exit();
    }

    if ($userModel->emailExists($email)) {
        header('Location: ../views/auth/register.php?status=email_exists&redirect=' . urlencode($redirect));
        exit();
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    if ($userModel->register($nama, $username, $email, $hashed)) {
        $loginRedirect = $redirect !== '' ? '&redirect=' . urlencode($redirect) : '';
        header('Location: ../views/auth/login.php?status=success' . $loginRedirect);
        exit();
    }

    header('Location: ../views/auth/register.php?status=failed&redirect=' . urlencode($redirect));
    exit();
}

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirect = safeRedirectPath($_POST['redirect'] ?? '');

    $userData = $userModel->getUserByLogin($login);

    if ($userData && password_verify($password, $userData['password_hash'])) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['nama_lengkap'] = $userData['nama_lengkap'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['role'] = $userData['role'];

        if ($userData['role'] === 'admin') {
            header('Location: ../views/admin/dashboard.php');
            exit();
        }

        if ($redirect !== '') {
            if (strpos($redirect, 'http') === 0 || strpos($redirect, '..') !== false) {
                $redirect = '';
            } elseif (strpos($redirect, 'views/') !== 0) {
                $redirect = '../views/' . ltrim($redirect, '/');
            } else {
                $redirect = '../' . ltrim($redirect, '/');
            }
        }

        if ($redirect !== '') {
            header('Location: ' . $redirect);
            exit();
        }

        header('Location: ../index.php');
        exit();
    }

    $errorRedirect = $redirect !== '' ? '&redirect=' . urlencode($redirect) : '';
    header('Location: ../views/auth/login.php?error=kredensial_salah' . $errorRedirect);
    exit();
}

if ($action === 'logout') {
    session_destroy();
    header('Location: ../index.php');
    exit();
}

header('Location: ../index.php');
exit();
