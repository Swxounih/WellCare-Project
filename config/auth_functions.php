<?php
/**
 * Authentication Functions
 * Helper functions for user authentication and session management
 */

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user ID from session
 * @return int|null
 */
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
}

/**
 * Get current user details from database
 * @param mysqli $conn Database connection
 * @return array|null User data or null if not logged in
 */
function getCurrentUser($conn) {
    if (!isLoggedIn()) {
        return null;
    }
    
    $user_id = getCurrentUserId();
    $stmt = $conn->prepare("SELECT user_id, email, username, password, fullname, phone, address FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    return $user;
}

/**
 * Require user to be logged in
 * Redirects to login page if not authenticated
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /WellCare%20Project/auth/Sign_in.php");
        exit;
    }
}

/**
 * Logout user
 * Clears session and redirects to homepage
 */
function logout() {
    session_destroy();
    $_SESSION = array();
    header("Location: /WellCare%20Project/index.php");
    exit;
}

/**
 * Generate CSRF token for forms
 * @return string
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * @param string $token
 * @return bool
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
