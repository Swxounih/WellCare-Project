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

// Get product_id from POST
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$user_id = getCurrentUserId();

if ($product_id <= 0) {
    header("Location: /WellCare%20Project/dashboard/Homepage.php?error=Invalid product");
    exit;
}

// Check if product exists
$stmt = $conn->prepare("SELECT product_id FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result->fetch_assoc()) {
    header("Location: /WellCare%20Project/dashboard/Homepage.php?error=Product not found");
    exit;
}
$stmt->close();

// Check if already in wishlist
$stmt = $conn->prepare("SELECT wishlist_id FROM wishlist WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($existing) {
    // Remove from wishlist if already there (toggle)
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->close();
} else {
    // Add to wishlist
    $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id, added_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to previous page or homepage
$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/WellCare%20Project/dashboard/Homepage.php';
header("Location: " . $referrer . (strpos($referrer, '?') ? '&' : '?') . "wishlist=1");
exit;
?>
