<?php
session_start();
include(__DIR__ . '/../config/db_connection.php');
include(__DIR__ . '/../config/auth_functions.php');
include(__DIR__ . '/../config/helpers.php');

requireLogin();

$user_id = getCurrentUserId();
$user = getCurrentUser($conn);

// Get cart items
$stmt = $conn->prepare("SELECT c.cartID, c.product_id, c.quantity, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ? ORDER BY c.added_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($cart_items)) {
    header("Location: /WellCare%20Project/dashboard/Cart.php");
    exit;
}

// Calculate total
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$tax = $subtotal * 0.10;
$total = $subtotal + $tax;

// Handle order submission
$order_created = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $shipping_address = isset($_POST['shipping_address']) ? trim($_POST['shipping_address']) : '';
    
    if (empty($shipping_address)) {
        $error_message = "Please enter a shipping address";
    } else {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Create order
            $stmt = $conn->prepare("INSERT INTO orders (user_id, totalAmount, shipping_address, status, orderDate) VALUES (?, ?, ?, 'To Pay', NOW())");
            $stmt->bind_param("ids", $user_id, $total, $shipping_address);
            $stmt->execute();
            $order_id = $conn->insert_id;
            $stmt->close();
            
            // Add order items
            foreach ($cart_items as $item) {
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiii", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                $stmt->execute();
                $stmt->close();
            }
            
            // Clear cart
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
            
            $conn->commit();
            $order_created = true;
        } catch (Exception $e) {
            $conn->rollback();
            $error_message = "Failed to create order: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Well Care Pharmacy</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --green: #2e8b57;
            --green-dark: #1a5c38;
            --green-light: #4caf7d;
            --green-btn: #3aaa6a;
            --bg: #f0f0f0;
            --white: #ffffff;
            --text: #111;
            --muted: #777;
            --border: #ddd;
            --price: #2e8b57;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .checkout-wrapper {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }

        .checkout-section {
            background: var(--white);
            border-radius: 12px;
            padding: 30px;
            border: 1px solid var(--border);
        }

        .section-title {
            font-family: 'DM Serif Display', serif;
            font-size: 24px;
            margin-bottom: 20px;
            color: var(--text);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text);
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--text);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(46, 139, 87, 0.1);
        }

        .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--border);
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .item-qty {
            font-size: 14px;
            color: var(--muted);
        }

        .item-price {
            font-weight: 600;
            min-width: 100px;
            text-align: right;
        }

        .order-summary {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding-top: 15px;
            border-top: 2px solid var(--border);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 15px;
        }

        .summary-row.total {
            font-size: 18px;
            font-weight: 700;
            color: var(--green);
        }

        .btn-checkout {
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            border: none;
            border-radius: 8px;
            background: var(--green-btn);
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-checkout:hover {
            background: var(--green-dark);
        }

        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .confirmation-box {
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
        }

        .confirmation-box h2 {
            color: #155724;
            margin-bottom: 15px;
        }

        .confirmation-box p {
            color: #155724;
            margin-bottom: 10px;
        }

        .order-id {
            font-family: 'DM Serif Display', serif;
            font-size: 28px;
            font-weight: bold;
            color: #28a745;
            margin: 20px 0;
        }

        .btn-continue {
            margin-top: 20px;
            padding: 12px 30px;
            background: var(--green-btn);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .btn-continue:hover {
            background: var(--green-dark);
        }

        @media (max-width: 768px) {
            .checkout-wrapper {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1 style="font-family: 'DM Serif Display', serif; margin-bottom: 10px;">Checkout</h1>

        <?php if ($order_created): ?>
            <div class="confirmation-box">
                <h2>✓ Order Placed Successfully!</h2>
                <p>Thank you for your purchase.</p>
                <div class="order-id">Order #<?php echo $order_id; ?></div>
                <p>Total Amount: <strong>₱<?php echo formatCurrency($total); ?></strong></p>
                <p>Status: <strong>To Pay</strong></p>
                <p>Shipping Address: <strong><?php echo safe($shipping_address); ?></strong></p>
                <a href="/WellCare%20Project/dashboard/Homepage.php" class="btn-continue">Continue Shopping</a>
                <a href="/WellCare%20Project/transaction/Orders.php" class="btn-continue" style="margin-left: 10px;">View Order</a>
            </div>
        <?php else: ?>
            <div class="checkout-wrapper">
                <!-- Left Side: Shipping Form -->
                <div class="checkout-section">
                    <h2 class="section-title">Shipping Information</h2>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="error" style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" value="<?php echo safe($user['fullname']); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" id="email" value="<?php echo safe($user['email']); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" id="phone" value="<?php echo safe($user['phone']); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="shipping_address">Shipping Address *</label>
                            <textarea id="shipping_address" name="shipping_address" required><?php echo isset($_POST['shipping_address']) ? safe($_POST['shipping_address']) : safe($user['address']); ?></textarea>
                        </div>

                        <button type="submit" name="place_order" class="btn-checkout">Place Order</button>
                    </form>
                </div>

                <!-- Right Side: Order Summary -->
                <div class="checkout-section">
                    <h2 class="section-title">Order Summary</h2>

                    <div class="order-item" style="border-bottom: 2px solid var(--border); font-weight: 600; padding-bottom: 10px;">
                        <div class="item-info">Product</div>
                        <div class="item-price">Amount</div>
                    </div>

                    <?php foreach ($cart_items as $item): ?>
                    <div class="order-item">
                        <div class="item-info">
                            <div class="item-name"><?php echo safe($item['name']); ?></div>
                            <div class="item-qty">Qty: <?php echo $item['quantity']; ?></div>
                        </div>
                        <div class="item-price">₱<?php echo formatCurrency($item['price'] * $item['quantity']); ?></div>
                    </div>
                    <?php endforeach; ?>

                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>₱<?php echo formatCurrency($subtotal); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Tax (10%):</span>
                            <span>₱<?php echo formatCurrency($tax); ?></span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span>₱<?php echo formatCurrency($total); ?></span>
                        </div>
                    </div>

                    <p style="font-size: 13px; color: var(--muted); margin-top: 20px;">
                        By placing this order, you agree to our Terms and Conditions.
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
