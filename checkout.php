<?php
include 'dbconnect.php';
include 'header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Please log in to proceed with the checkout.');
        window.location.href = 'login.php';
    </script>";
    exit;
}

$flash_message = $_SESSION['checkout_flash'] ?? '';
$flash_type = $_SESSION['checkout_flash_type'] ?? 'success';
unset($_SESSION['checkout_flash'], $_SESSION['checkout_flash_type']);

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "<script>
            alert('Your cart is empty. Please add products before checkout.');
            window.location.href = 'cart.php';
        </script>";
        exit;
    }

    $user_id = (int) $_SESSION['user_id'];

    // Fetch user details
    $stmt_user = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt_user->execute(['user_id' => $user_id]);
    $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

    if (
        !$user ||
        empty($user['address']) ||
        empty($user['city']) ||
        empty($user['postal_code']) ||
        empty($user['country'])
    ) {
        echo "<script>
            alert('Please fill in your address details in your account.');
            window.location.href = 'my-account.php';
        </script>";
        exit;
    }

    // Validate payment method
    $payment_method = $_POST['payment_method'] ?? 'cod';
    $allowed_payment_methods = ['cod', 'online_payment'];
    if (!in_array($payment_method, $allowed_payment_methods, true)) {
        $payment_method = 'cod';
    }

    // Calculate cart total
    $cart_total = 0;
    foreach ($_SESSION['cart'] as $product_id => $product) {
        $qty = isset($product['qty']) ? (int) $product['qty'] : 1;
        $price = isset($product['price']) ? (float) $product['price'] : 0;
        $cart_total += ($price * $qty);
    }

    if ($cart_total <= 0) {
        echo "<script>
            alert('Your cart total is invalid. Please try again.');
            window.location.href = 'cart.php';
        </script>";
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Insert order
        $stmt_order = $pdo->prepare("
            INSERT INTO orders 
            (user_id, first_name, last_name, email, telephone, address, city, postal_code, country, total, payment_method, status)
            VALUES
            (:user_id, :first_name, :last_name, :email, :telephone, :address, :city, :postal_code, :country, :total, :payment_method, :status)
        ");

        $stmt_order->execute([
            'user_id' => $user_id,
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'telephone' => $user['telephone'],
            'address' => $user['address'],
            'city' => $user['city'],
            'postal_code' => $user['postal_code'],
            'country' => $user['country'],
            'total' => $cart_total,
            'payment_method' => $payment_method,
            'status' => 'pending'
        ]);

        $order_id = $pdo->lastInsertId();

        // Insert order items
        $stmt_item = $pdo->prepare("
            INSERT INTO order_items 
            (order_id, product_id, product_name, quantity, price, total)
            VALUES
            (:order_id, :product_id, :product_name, :quantity, :price, :total)
        ");

        foreach ($_SESSION['cart'] as $product_id => $product) {
            $qty = isset($product['qty']) ? (int) $product['qty'] : 1;
            $price = isset($product['price']) ? (float) $product['price'] : 0;
            $product_name = $product['product_name'] ?? 'Product';
            $item_total = round($price * $qty, 2);

            $stmt_item->execute([
                'order_id' => $order_id,
                'product_id' => $product_id,
                'product_name' => $product_name,
                'quantity' => $qty,
                'price' => $price,
                'total' => $item_total
            ]);
        }

        $pdo->commit();

        // Clear cart after successful order
        unset($_SESSION['cart']);

        // Success message
        $_SESSION['checkout_flash'] = "Order placed successfully. Your Order ID is #{$order_id}.";
        $_SESSION['checkout_flash_type'] = "success";

        echo "<script>
            window.location.href = 'checkout.php';
        </script>";
        exit;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        echo "<script>
            alert('Order could not be placed. Please try again.');
            window.location.href = 'cart.php';
        </script>";
        exit;
    }
}
?>

<section class="checkout-area ptb-90">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="checkout-section">

                    <?php if (!empty($flash_message)): ?>
                        <div class="alert alert-<?= htmlspecialchars($flash_type) ?> text-center">
                            <?= htmlspecialchars($flash_message) ?>
                        </div>
                        <div class="text-center mt-20">
                            <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                        </div>
                    <?php else: ?>
                        <h4>Placing your order...</h4>
                        <p>Your order will be saved securely in the database.</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>