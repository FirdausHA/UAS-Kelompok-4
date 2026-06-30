<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';
require_once '../config/midtrans.php';
require_once '../models/Order.php';
require_once '../models/Studio.php';
require_once '../models/MidtransService.php';
require_once '../includes/auth_guard.php';
require_once '../includes/helpers.php'; // Added helpers

requirePelanggan();

$database = new Database();
$db = $database->getConnection();
$orderModel = new Order($db);
$studioModel = new Studio($db);
$midtransService = new MidtransService();

$action = isset($_GET['action']) ? $_GET['action'] : '';

/**
 * Handle creating Snap token
 */
if ($action === 'create-snap' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $order_db_id = isset($_POST['order_db_id']) ? (int) $_POST['order_db_id'] : 0;
    
    if ($order_db_id === 0) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid order ID'
        ]);
        exit;
    }
    
    $order = $orderModel->getById($order_db_id);

    if (!$order) {
        echo json_encode([
            'success' => false,
            'error' => 'Order not found (ID: ' . $order_db_id . ')'
        ]);
        exit;
    }

    if ((int)$order['user_id'] !== (int)$_SESSION['user_id']) {
        echo json_encode([
            'success' => false,
            'error' => 'This order does not belong to you'
        ]);
        exit;
    }

    $studio = $studioModel->getById($order['studio_id']);
    if (!$studio) {
        echo json_encode([
            'success' => false,
            'error' => 'Studio not found for this order'
        ]);
        exit;
    }

    $user = getUserById($db, $order['user_id']);
    if (!$user) {
        echo json_encode([
            'success' => false,
            'error' => 'User not found'
        ]);
        exit;
    }

    // Prepare transaction details
    $params = [
        'transaction_details' => [
            'order_id' => $order['order_code'],
            'gross_amount' => (int)$order['total']
        ],
        'customer_details' => [
            'first_name' => $user['nama_lengkap'] ?? 'Customer',
            'email' => $user['email'] ?? '',
            'phone' => $user['no_telepon'] ?? ''
        ],
        'item_details' => [
            [
                'id' => $order['studio_id'],
                'price' => (int)$order['total'],
                'quantity' => 1,
                'name' => $studio['nama'] . ' (' . $order['addon_label'] . ')'
            ]
        ]
    ];

    try {
        $response = $midtransService->createSnapTransaction($params);

        if ($response['http_code'] === 201) {
            echo json_encode([
                'success' => true,
                'snap_token' => $response['response']['token'],
                'redirect_url' => $response['response']['redirect_url']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Midtrans API error',
                'http_code' => $response['http_code'],
                'details' => $response['response']
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Exception: ' . $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
    exit;
}

/**
 * Handle payment callback from Midtrans (webhook)
 */
if ($action === 'callback' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Midtrans notification
    // Note: For production, you should set this URL in Midtrans Dashboard
    // This endpoint should be public (accessible from internet)
    
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);
    
    // Verify the transaction status
    $order_code = $data['order_id'] ?? '';
    if ($order_code) {
        $order = $orderModel->getByCode($order_code);
        if ($order) {
            $status = $data['transaction_status'] ?? '';
            $payment_type = $data['payment_type'] ?? '';
            $fraud_status = $data['fraud_status'] ?? '';
            
            // Update order status based on Midtrans response
            if ($status === 'capture' && $fraud_status === 'accept') {
                $orderModel->updateStatus($order['id'], 'confirmed');
            } elseif ($status === 'settlement') {
                $orderModel->updateStatus($order['id'], 'confirmed');
            } elseif ($status === 'pending') {
                // Payment is pending
            } elseif ($status === 'deny' || $status === 'cancel' || $status === 'expire') {
                $orderModel->updateStatus($order['id'], 'cancelled');
            }
        }
    }
    http_response_code(200);
    echo json_encode(['status' => 'ok']);
    exit;
}

/**
 * Handle completion after payment (redirect from Midtrans)
 */
if ($action === 'finish') {
    $order_code = isset($_GET['order_id']) ? $_GET['order_id'] : '';
    
    if ($order_code) {
        $order = $orderModel->getByCode($order_code);
        if ($order) {
            // Update order status
            $orderModel->confirmMidtransPayment($order['id'], 'midtrans');
        }
    }
    
    header('Location: ../views/checkout.php?order_code=' . $order_code . '&status=success');
    exit;
}

/**
 * Handle unfinish payment (redirect from Midtrans)
 */
if ($action === 'unfinish') {
    $order_code = isset($_GET['order_id']) ? $_GET['order_id'] : '';
    header('Location: ../views/checkout.php?order_code=' . $order_code . '&status=unfinish');
    exit;
}

/**
 * Handle error payment (redirect from Midtrans)
 */
if ($action === 'error') {
    $order_code = isset($_GET['order_id']) ? $_GET['order_id'] : '';
    header('Location: ../views/checkout.php?order_code=' . $order_code . '&status=error');
    exit;
}

header('Location: ../index.php');
exit;
