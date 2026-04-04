<?php
/**
 * Helper Functions
 * Common utility functions used throughout the application
 */

/**
 * Sanitize input to prevent XSS attacks
 * @param string $input
 * @return string
 */
function safe($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email format
 * @param string $email
 * @return bool
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (basic validation)
 * @param string $phone
 * @return bool
 */
function validatePhone($phone) {
    return preg_match('/^[0-9\-\+\(\)\s]{10,}$/', $phone);
}

/**
 * Format currency for display
 * @param float $amount
 * @return string
 */
function formatCurrency($amount) {
    return number_format($amount, 2);
}

/**
 * Format date for display
 * @param string $date
 * @param string $format
 * @return string
 */
function formatDate($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

/**
 * Get status color class for order status
 * @param string $status
 * @return string CSS class name
 */
function getStatusColor($status) {
    $colors = [
        'To Pay' => 'status-warning',
        'To Ship' => 'status-info',
        'To Receive' => 'status-primary',
        'Completed' => 'status-success',
        'Cancelled' => 'status-danger',
        'Return/Refund' => 'status-secondary'
    ];
    
    return isset($colors[$status]) ? $colors[$status] : 'status-secondary';
}

/**
 * Redirect with message in session
 * @param string $url
 * @param string $message
 * @param string $type (success, error, info, warning)
 */
function redirectWithMessage($url, $message, $type = 'info') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: " . $url);
    exit;
}

/**
 * Display message from session
 * @return string HTML or empty string
 */
function displayMessage() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info';
        
        $colors = [
            'success' => '#d4edda',
            'error' => '#f8d7da',
            'warning' => '#fff3cd',
            'info' => '#d1ecf1'
        ];
        
        $textColors = [
            'success' => '#155724',
            'error' => '#721c24',
            'warning' => '#856404',
            'info' => '#0c5460'
        ];
        
        $bg = isset($colors[$type]) ? $colors[$type] : $colors['info'];
        $textColor = isset($textColors[$type]) ? $textColors[$type] : $textColors['info'];
        
        $html = "<div style='background: {$bg}; color: {$textColor}; padding: 12px; border-radius: 6px; margin-bottom: 15px;'>";
        $html .= safe($message);
        $html .= "</div>";
        
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        
        return $html;
    }
    
    return '';
}

/**
 * Get file extension
 * @param string $filename
 * @return string
 */
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Validate file upload
 * @param array $file $_FILES array element
 * @param array $allowed_types
 * @param int $max_size in bytes
 * @return array ['valid' => bool, 'error' => string]
 */
function validateFileUpload($file, $allowed_types = ['jpg', 'jpeg', 'png', 'gif'], $max_size = 5242880) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['valid' => false, 'error' => 'File upload error'];
    }
    
    if ($file['size'] > $max_size) {
        return ['valid' => false, 'error' => 'File size exceeds limit'];
    }
    
    $ext = getFileExtension($file['name']);
    if (!in_array($ext, $allowed_types)) {
        return ['valid' => false, 'error' => 'File type not allowed'];
    }
    
    return ['valid' => true, 'error' => ''];
}

/**
 * Generate random string
 * @param int $length
 * @return string
 */
function generateRandomString($length = 10) {
    return bin2hex(random_bytes($length / 2));
}
?>
