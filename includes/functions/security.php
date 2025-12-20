<?php
/**
 * Security Functions for IlyassFit
 * Handles CSRF protection and rate limiting
 * 
 * @package IlyassFit
 * @version 1.0.0
 */

// ============================================
// CSRF PROTECTION FUNCTIONS
// ============================================

/**
 * Generate a CSRF token and store it in session
 * 
 * @return string The generated token
 */
function generateCsrfToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token from form submission
 * Uses timing-safe comparison to prevent timing attacks
 * 
 * @param string|null $token The token to validate
 * @return bool True if valid, false otherwise
 */
function validateCsrfToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    
    // Use hash_equals for timing-safe comparison
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get CSRF token input field for forms
 * Returns HTML input element ready to insert in forms
 * 
 * @return string HTML input field with CSRF token
 */
function csrfTokenField() {
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Regenerate CSRF token (call after successful form submission)
 * Good practice to prevent token reuse
 * 
 * @return string The new token
 */
function regenerateCsrfToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

// ============================================
// RATE LIMITING FUNCTIONS
// ============================================

/**
 * Check if an IP has exceeded rate limit for a specific action
 * 
 * @param string $action Action identifier (e.g., 'login', 'contact')
 * @param int $maxAttempts Maximum attempts allowed
 * @param int $timeWindow Time window in seconds (default: 900 = 15 minutes)
 * @return bool True if rate limit exceeded, false otherwise
 */
function isRateLimited($action, $maxAttempts = 5, $timeWindow = 900) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $key = "rate_limit_{$action}_{$ip}";
    
    // Initialize or get attempts data
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'count' => 0,
            'first_attempt' => time(),
            'last_attempt' => time()
        ];
        return false;
    }
    
    $data = $_SESSION[$key];
    $currentTime = time();
    
    // Reset if time window has passed
    if ($currentTime - $data['first_attempt'] > $timeWindow) {
        $_SESSION[$key] = [
            'count' => 0,
            'first_attempt' => $currentTime,
            'last_attempt' => $currentTime
        ];
        return false;
    }
    
    // Check if limit exceeded
    return $data['count'] >= $maxAttempts;
}

/**
 * Increment rate limit counter for an action
 * 
 * @param string $action Action identifier
 * @return int Current attempt count
 */
function incrementRateLimit($action) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $key = "rate_limit_{$action}_{$ip}";
    $currentTime = time();
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'count' => 1,
            'first_attempt' => $currentTime,
            'last_attempt' => $currentTime
        ];
    } else {
        $_SESSION[$key]['count']++;
        $_SESSION[$key]['last_attempt'] = $currentTime;
    }
    
    return $_SESSION[$key]['count'];
}

/**
 * Reset rate limit counter for an action
 * Call this after successful authentication or form submission
 * 
 * @param string $action Action identifier
 * @return void
 */
function resetRateLimit($action) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $key = "rate_limit_{$action}_{$ip}";
    
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}

/**
 * Get remaining time until rate limit reset
 * 
 * @param string $action Action identifier
 * @param int $timeWindow Time window in seconds
 * @return int Seconds remaining, 0 if not limited
 */
function getRateLimitResetTime($action, $timeWindow = 900) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $key = "rate_limit_{$action}_{$ip}";
    
    if (!isset($_SESSION[$key])) {
        return 0;
    }
    
    $data = $_SESSION[$key];
    $elapsed = time() - $data['first_attempt'];
    $remaining = $timeWindow - $elapsed;
    
    return max(0, $remaining);
}

/**
 * Get user-friendly time remaining message
 * 
 * @param int $seconds Seconds remaining
 * @return string Formatted message
 */
function formatRateLimitMessage($seconds) {
    if ($seconds <= 0) {
        return "You can try again now.";
    }
    
    $minutes = ceil($seconds / 60);
    
    if ($minutes === 1) {
        return "Please wait 1 minute before trying again.";
    }
    
    return "Please wait {$minutes} minutes before trying again.";
}

// ============================================
// INPUT SANITIZATION HELPERS
// ============================================

/**
 * Sanitize user input for database insertion
 * 
 * @param string $input Raw user input
 * @return string Sanitized input
 */
function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

/**
 * Validate email address
 * 
 * @param string $email Email to validate
 * @return bool True if valid, false otherwise
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (basic validation)
 * 
 * @param string $phone Phone number to validate
 * @return bool True if valid, false otherwise
 */
function isValidPhone($phone) {
    // Remove spaces, dashes, and parentheses
    $phone = preg_replace('/[\s\-\(\)]/', '', $phone);
    
    // Check if it's between 8 and 15 digits
    return preg_match('/^[0-9]{8,15}$/', $phone);
}

/**
 * Generate secure random string
 * Useful for tokens, passwords, etc.
 * 
 * @param int $length Length of the string
 * @return string Random string
 */
function generateSecureRandomString($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// ============================================
// SESSION SECURITY HELPERS
// ============================================

/**
 * Check if session is expired
 * 
 * @param int $maxLifetime Maximum session lifetime in seconds
 * @return bool True if expired, false otherwise
 */
function isSessionExpired($maxLifetime = 3600) {
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
        return false;
    }
    
    if (time() - $_SESSION['last_activity'] > $maxLifetime) {
        return true;
    }
    
    $_SESSION['last_activity'] = time();
    return false;
}

/**
 * Destroy session and clean up
 * 
 * @return void
 */
function destroySession() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }
}
?>
