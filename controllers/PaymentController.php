<?php
session_start();

require_once '../config/database.php';
require_once '../models/Order.php';
require_once '../includes/auth_guard.php';

requirePelanggan();

$upload_dir = dirname(__DIR__) . '/uploads/bukti/';

if (!isset($_GET['action']) || $_GET['action'] !== 'konfirmasi') {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php#katalog');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$orderModel = new Order($db);

$order_db_id = isset($_POST['order_db_id']) ? (int) $_POST['order_db_id'] : 0;
$studio_id = isset($_POST['studio_id']) ? (int) $_POST['studio_id'] : 0;
$tanggal = isset($_POST['tanggal']) ? trim($_POST['tanggal']) : '';
$waktu = isset($_POST['waktu']) ? trim($_POST['waktu']) : '';
$addon_label = isset($_POST['addon_label']) ? trim($_POST['addon_label']) : '';
$total = isset($_POST['total']) ? (int) $_POST['total'] : 0;
$payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : 'bank';

function redirectCheckout($params, $status) {
    $params['status'] = $status;
    header('Location: ../views/checkout.php?' . http_build_query($params));
    exit();
}

$redirectParams = [
    'studio_id' => $studio_id,
    'tanggal' => $tanggal,
    'waktu' => $waktu,
    'addon_label' => $addon_label,
    'total' => $total,
];

$order = $orderModel->getById($order_db_id);
if (!$order || (int) $order['user_id'] !== (int) $_SESSION['user_id']) {
    redirectCheckout($redirectParams, 'error');
}

$redirectParams['order_db_id'] = $order_db_id;

if (!isset($_FILES['bukti']) || $_FILES['bukti']['error'] !== UPLOAD_ERR_OK) {
    redirectCheckout($redirectParams, 'error');
}

$file = $_FILES['bukti'];
$allowed = ['image/png', 'image/jpeg', 'image/jpg', 'application/pdf'];
$max_size = 5 * 1024 * 1024;

if ($file['size'] > $max_size) {
    redirectCheckout($redirectParams, 'error');
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mime, $allowed)) {
    redirectCheckout($redirectParams, 'error');
}

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'bukti_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
$destination = $upload_dir . $filename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    redirectCheckout($redirectParams, 'error');
}

if (!$orderModel->confirmPayment($order_db_id, $filename, $payment_method)) {
    redirectCheckout($redirectParams, 'error');
}

unset($_SESSION['checkout_order_id']);

$redirectParams['order_code'] = $order['order_code'];
redirectCheckout($redirectParams, 'success');
