<?php
// ============================================================
//  db.php  –  Well Care Pharmacy Database Connection
//  Procedural Style using mysqli_connect()
//  Place this file in your project ROOT folder
// ============================================================

$db_host = 'localhost';
$db_user = 'root';    // change if your XAMPP MySQL user is different
$db_pass = '';        // change if you set a MySQL password
$db_name = 'wellcare_pharmacy';

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8mb4 (supports Filipino characters & emojis)
mysqli_set_charset($conn, 'utf8mb4');

// ============================================================
//  Session – start if not already started
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
//  Auth Helpers
// ============================================================

// Check if a customer is logged in
function is_logged_in() {
    return isset($_SESSION['customerID']) && !empty($_SESSION['customerID']);
}

// Redirect to sign-in page if not logged in
// Usage: require_login(); at the top of any protected page
function require_login($redirect = 'Sign_in.php') {
    if (!is_logged_in()) {
        header('Location: ' . $redirect);
        exit();
    }
}

// Get the current logged-in customer ID
function current_user_id() {
    return isset($_SESSION['customerID']) ? (int)$_SESSION['customerID'] : null;
}