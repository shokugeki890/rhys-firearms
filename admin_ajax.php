<?php
require_once 'database.php';
require_once 'auth.php';
requireAdmin(); // Only admins can access this

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'order_details') {
    $order_id = (int)($_GET['id'] ?? 0);

    if ($order_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
        exit;
    }

    try {
        // Get order info
        $stmt = $pdo->prepare("SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?");
        $stmt->execute([$order_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Order not found']);
            exit;
        }

        // Get order items
        $stmt = $pdo->prepare("SELECT oi.*, p.name as product_name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmt->execute([$order_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'order' => $order,
            'items' => $items
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }

} elseif ($action === 'transaction_details') {
    $tx_id = (int)($_GET['id'] ?? 0);

    if ($tx_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid transaction ID']);
        exit;
    }

    try {
        // Get transaction info
        $stmt = $pdo->prepare("SELECT t.*, u.username FROM transactions t LEFT JOIN users u ON t.user_id = u.id WHERE t.id = ?");
        $stmt->execute([$tx_id]);
        $tx = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tx) {
            echo json_encode(['success' => false, 'message' => 'Transaction not found']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'tx' => $tx
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
