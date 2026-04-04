<?php
session_start();
include(__DIR__ . '/db_connection.php');
include(__DIR__ . '/auth_functions.php');
include(__DIR__ . '/helpers.php');

// Require login
if (!isLoggedIn()) {
    header("Location: /WellCare%20Project/auth/Sign_in.php");
    exit;
}

// Get product_id and quantity from POST
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$user_id = getCurrentUserId();

if ($product_id <= 0 || $quantity <= 0) {
    header("Location: /WellCare%20Project/dashboard/Homepage.php?error=Invalid product or quantity");
    exit;
}

// Check if product exists
$stmt = $conn->prepare("SELECT product_id, stock FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product || $product['stock'] <= 0) {
    header("Location: /WellCare%20Project/dashboard/Homepage.php?error=Product out of stock");
    exit;
}

// Check if product already in cart
$stmt = $conn->prepare("SELECT cartID, quantity FROM cart WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($existing) {
    // Update quantity if exists
    $new_qty = $existing['quantity'] + $quantity;
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cartID = ?");
    $stmt->bind_param("ii", $new_qty, $existing['cartID']);
    $stmt->execute();
    $stmt->close();
} else {
    // Insert new cart item
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, added_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to previous page or homepage
$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/WellCare%20Project/dashboard/Homepage.php';
header("Location: " . $referrer . (strpos($referrer, '?') ? '&' : '?') . "added=1");
exit;
?>
