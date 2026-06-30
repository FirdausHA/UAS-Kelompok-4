<?php
session_start();

require_once '../config/database.php';
require_once '../models/User.php';
require_once '../includes/auth_guard.php';

requireAdmin();

if (!isset($_GET['action']) || $_GET['action'] !== 'toggle_status' || !isset($_GET['id'])) {
    header('Location: ../views/admin/users.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

if ($_GET['action'] === 'toggle_status' && isset($_GET['id'])) {
    $userModel->toggleStatus((int) $_GET['id']);
    header('Location: ../views/admin/users.php?status=updated');
    exit();
}

header('Location: ../views/admin/users.php');
exit();
