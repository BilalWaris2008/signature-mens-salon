<?php
include '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
    $status = trim($_POST['status'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');

    if ($order_id <= 0 || $status === '' || $payment_method === '') {
        echo "<script>
            alert('Invalid order data.');
            window.location.href = 'manage-orders.php';
        </script>";
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE orders 
            SET status = :status, payment_method = :payment_method 
            WHERE order_id = :order_id
        ");

        $stmt->execute([
            'status' => $status,
            'payment_method' => $payment_method,
            'order_id' => $order_id
        ]);

        echo "<script>
            alert('Order status and payment method updated successfully.');
            window.location.href = 'manage-orders.php';
        </script>";
        exit;
    } catch (Exception $e) {
        echo "<script>
            alert('Something went wrong while updating the order.');
            window.location.href = 'manage-orders.php';
        </script>";
        exit;
    }
}
