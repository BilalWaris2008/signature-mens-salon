<?php
include 'header.php';
include '../dbconnect.php';

// Fetch current logged-in user's role to restrict access
$user_id = $_SESSION['user_id'];
$stmt_role = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
$stmt_role->execute(['user_id' => $user_id]);
$user = $stmt_role->fetch(PDO::FETCH_ASSOC);

// Restrict access for non-admin users
if ($user['role'] !== 'admin') {
    echo "<script>alert('Access denied.'); window.location.href = 'index.php';</script>";
    exit;
}

$message = "";

// Fetch all orders
$stmt = $pdo->prepare("SELECT * FROM orders ORDER BY created_at DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .orders-table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .orders-table-main {
        width: 100%;
        min-width: 1100px;
        margin-bottom: 0;
    }

    .orders-table-main th,
    .orders-table-main td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .order-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .order-actions a {
        text-decoration: none;
    }

    .order-actions .ce5 {
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

        .order-actions {
            justify-content: flex-end;
            width: 100%;
        }

        .order-actions a,
        .order-actions button {
            width: 100%;
        }

        .order-actions .ce5 {
            width: 100%;
            display: block;
        }

        .modal-dialog {
            margin: 1rem;
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

<div class="container col-12 ptb-100">

    <!-- Display Success or Error Messages -->
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <div class="orders-table-wrapper">
        <table class="table table-bordered mt-4 orders-table-main">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td data-label="Order ID"><?= $order['order_id'] ?></td>
                        <td data-label="Customer Name"><?= $order['first_name'] . ' ' . $order['last_name'] ?></td>
                        <td data-label="Email"><?= $order['email'] ?></td>
                        <td data-label="Total">PKR <?= number_format($order['total'], 2) ?></td>
                        <td data-label="Payment Method"><?= ucfirst($order['payment_method']) ?></td>
                        <td data-label="Status"><?= ucfirst($order['status']) ?></td>
                        <td data-label="Order Date"><?= date('F d, Y', strtotime($order['created_at'])) ?></td>
                        <td data-label="Actions">
                            <div class="order-actions">
                                <button class="btn btn-primary ce5 mb-1" data-bs-toggle="modal" data-bs-target="#editOrderModal<?= $order['order_id'] ?>">Edit Status</button>
                                <a href="view-order-details.php?order_id=<?= $order['order_id'] ?>" class="btn btn-primary ce5 mb-1">View Details</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Order Status Modal -->
                    <div class="modal fade" id="editOrderModal<?= $order['order_id'] ?>" tabindex="-1" aria-labelledby="editOrderModalLabel<?= $order['order_id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="post" action="edit-order-status.php">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editOrderModalLabel<?= $order['order_id'] ?>">Edit Order Status</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">

                                        <!-- Payment Method Dropdown -->
                                        <div class="form-group">
                                            <label for="payment_method">Payment Method</label>
                                            <select class="form-control" name="payment_method" required>
                                                <option value="online_payment" <?= ($order['payment_method'] == 'online_payment') ? 'selected' : '' ?>>Online Payment</option>
                                                <option value="cod" <?= ($order['payment_method'] == 'cod') ? 'selected' : '' ?>>Cash on Delivery (COD)</option>
                                            </select>
                                        </div>

                                        <!-- Status Dropdown -->
                                        <div class="form-group">
                                            <label for="status">Order Status</label>
                                            <select class="form-control" name="status" required>
                                                <option value="unpaid" <?= ($order['status'] == 'unpaid') ? 'selected' : '' ?>>Unpaid</option>
                                                <option value="pending" <?= ($order['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                                                <option value="paid" <?= ($order['status'] == 'paid') ? 'selected' : '' ?>>Paid</option>
                                                <option value="packed" <?= ($order['status'] == 'packed') ? 'selected' : '' ?>>Packed</option>
                                                <option value="shipped" <?= ($order['status'] == 'shipped') ? 'selected' : '' ?>>Shipped</option>
                                                <option value="delivered" <?= ($order['status'] == 'delivered') ? 'selected' : '' ?>>Delivered</option>
                                                <option value="cancelled" <?= ($order['status'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary ce5 mb-1" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary ce5 mb-1">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>