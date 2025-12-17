<?php
// Start session with secure settings
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        // Secure session configuration
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
        ini_set('session.cookie_samesite', 'Strict');
        session_start();
    }
}

// Check if admin is logged in
function isLoggedIn() {
    startSecureSession();
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

// Require admin login (redirect if not logged in)
function requireLogin($redirectTo = 'login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirectTo");
        exit;
    }
}

// Regenerate session ID for security
function regenerateSession() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
}

// Logout function
function logout($redirectTo = 'login.php') {
    startSecureSession();
    $_SESSION = array();
    
    // Destroy session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
    header("Location: $redirectTo");
    exit;
}

// Get logged in admin username
function getAdminUsername() {
    return $_SESSION['admin_username'] ?? 'Admin';
}

// Get logged in admin ID
function getAdminId() {
    return $_SESSION['admin_id'] ?? null;
}
?>
