<?php
session_start();

require_once '../config/database.php';
require_once '../models/Order.php';
require_once '../includes/auth_guard.php';

requireAdmin();

$database = new Database();
$db = $database->getConnection();
$orderModel = new Order($db);

if (!isset($_GET['action'])) {
    header('Location: ../views/admin/orders.php');
    exit();
}

if ($_GET['action'] === 'update_status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';

    if ($id > 0) {
        $orderModel->updateStatus($id, $status);
    }

    header('Location: ../views/admin/orders.php?status=updated');
    exit();
}

header('Location: ../views/admin/orders.php');
exit();
