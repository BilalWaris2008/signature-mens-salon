<?php
include 'header.php';
include 'dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Please log in to manage your orders.');
        window.location.href = 'login.php';
    </script>";
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$message = '';

// Handle order cancellation
if (isset($_GET['cancel_order'])) {
    $order_id = (int) $_GET['cancel_order'];

    try {
        // Make sure the order belongs to the logged-in user
        $stmt_check = $pdo->prepare("
            SELECT order_id
            FROM orders
            WHERE order_id = :order_id AND user_id = :user_id
            LIMIT 1
        ");
        $stmt_check->execute([
            'order_id' => $order_id,
            'user_id' => $user_id
        ]);

        $order_exists = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($order_exists) {
            $pdo->beginTransaction();

            // Delete order items first
            $stmt_delete_items = $pdo->prepare("
                DELETE FROM order_items
                WHERE order_id = :order_id
            ");
            $stmt_delete_items->execute(['order_id' => $order_id]);

            // Then delete the order
            $stmt_delete_order = $pdo->prepare("
                DELETE FROM orders
                WHERE order_id = :order_id AND user_id = :user_id
            ");
            $stmt_delete_order->execute([
                'order_id' => $order_id,
                'user_id' => $user_id
            ]);

            $pdo->commit();
            $message = 'Order successfully canceled.';
        } else {
            $message = 'Order not found.';
        }
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $message = 'Something went wrong while canceling the order.';
    }
}

// Fetch user's orders from the database
$stmt = $pdo->prepare("
    SELECT order_id, total, created_at AS order_date, payment_method, status
    FROM orders
    WHERE user_id = :user_id
    ORDER BY created_at DESC
");
$stmt->execute(['user_id' => $user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to generate PDF download link
function generatePDFLink($order_id)
{
    return "<a href='admin/download-order-pdf.php?order_id={$order_id}' class='btn btn-primary ce5'>Download PDF</a>";
}
?>

<style>
    .orders-table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .orders-table-main {
        width: 100%;
        min-width: 900px;
        margin-bottom: 0;
    }

    .orders-table-main th,
    .orders-table-main td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .order-action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .order-action-buttons a {
        text-decoration: none;
    }

    .order-action-buttons .ce5 {
        margin: 0;
    }

    @media (max-width: 768px) {
        .orders-table-main {
            min-width: 0;
        }

        .orders-table-main thead {
            display: none;
        }

        .orders-table-main,
        .orders-table-main tbody,
        .orders-table-main tr,
        .orders-table-main td {
            display: block;
            width: 100%;
        }

        .orders-table-main tr {
            margin-bottom: 15px;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .orders-table-main td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            border: none;
            border-bottom: 1px solid #f0f0f0;
            white-space: normal;
            text-align: right;
            font-size: 14px;
        }

        .orders-table-main td:last-child {
            border-bottom: none;
            align-items: flex-start;
        }

        .orders-table-main td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #333;
            text-align: left;
            padding-right: 15px;
            flex: 1;
        }

        .orders-table-main td>* {
            flex: 1;
            text-align: right;
        }

        .order-action-buttons {
            justify-content: flex-end;
            width: 100%;
        }

        .order-action-buttons a,
        .order-action-buttons button {
            width: 100%;
        }

        .order-action-buttons .ce5 {
            width: 100%;
            display: block;
        }
    }
</style>

<section class="breadcrumbs-area ptb-100 bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title">Manage Orders</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Manage Orders</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="manage-orders-area pt-90 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="orders-table">
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($orders)): ?>
                        <div class="table-responsive orders-table-wrapper">
                            <table class="table table-striped orders-table-main">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Total (PKR)</th>
                                        <th>Order Date</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td data-label="Order ID"><?= htmlspecialchars($order['order_id']) ?></td>
                                            <td data-label="Total (PKR)"><?= number_format((float)$order['total'], 2) ?></td>
                                            <td data-label="Order Date"><?= date('F d, Y', strtotime($order['order_date'])) ?></td>
                                            <td data-label="Payment Method"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $order['payment_method']))) ?></td>
                                            <td data-label="Status"><?= htmlspecialchars(ucfirst($order['status'])) ?></td>
                                            <td data-label="Actions">
                                                <div class="order-action-buttons">
                                                    <?php if ($order['status'] === 'unpaid' && $order['payment_method'] === 'online_payment'): ?>
                                                        <a href="proceed_to_payment.php?order_id=<?= $order['order_id'] ?>">
                                                            <button class="btn btn-primary ce5 mb-1">Proceed to Payment</button>
                                                        </a>
                                                        <a href="?cancel_order=<?= $order['order_id'] ?>"
                                                            onclick="return confirm('Are you sure you want to cancel this order?')">
                                                            <button class="btn btn-primary ce5 mb-1">Cancel Order</button>
                                                        </a>
                                                    <?php elseif ($order['status'] === 'pending'): ?>
                                                        <a href="?cancel_order=<?= $order['order_id'] ?>"
                                                            onclick="return confirm('Are you sure you want to cancel this order?')">
                                                            <button class="btn btn-primary ce5 mb-1">Cancel Order</button>
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-primary ce5 mb-1" disabled>Cancel Order</button>
                                                    <?php endif; ?>

                                                    <?= generatePDFLink($order['order_id']) ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p style="text-align: center; font-size: 16px; color: #555; padding: 20px;">
                            You have no orders.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>