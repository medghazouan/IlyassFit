<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors in output

// Set JSON header
header('Content-Type: application/json');

// Start output buffering to catch any errors
ob_start();

try {
    require_once '../includes/config/db_config.php';
    require_once '../includes/functions/crud.php';
    require_once '../includes/functions/security.php';
    require_once '../includes/functions/auth.php';
    
    // Start session for CSRF validation
    startSecureSession();

    $response = ['success' => false, 'message' => ''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check rate limit first (10 messages / 1 hour - balanced protection)
        if (isRateLimited('contact', 10, 3600)) {
            $remainingTime = getRateLimitResetTime('contact', 3600);
            $response['message'] = formatRateLimitMessage($remainingTime);
            ob_end_clean();
            echo json_encode($response);
            exit;
        }
        
        // Validate CSRF token
        if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $response['message'] = 'Invalid security token. Please refresh the page and try again.';
            incrementRateLimit('contact'); // Count CSRF failure
            ob_end_clean();
            echo json_encode($response);
            exit;
        }
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        // Validation
        if (empty($fullName) || empty($email) || empty($telephone) || empty($message)) {
            $response['message'] = "All fields are required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = "Invalid email address";
        } else {
            $data = [
                'full_name' => $fullName,
                'telephone' => $telephone,
                'email' => $email,
                'message' => $message
            ];
            
            if (create($pdo, 'messages', $data)) {
                // Get first name only for personalized message
                $firstName = explode(' ', $fullName)[0];
                
                $response['success'] = true;
                $response['firstName'] = $firstName;
                $response['message'] = "Thank you, {$firstName}! Your message has been received. I'm excited to help you on your fitness journey. I'll personally review your message and get back to you within 24 hours. Let's make it happen!";
                incrementRateLimit('contact'); // Count successful submission
            } else {
                $response['message'] = "Failed to send message. Please try again.";
            }
        }
    } else {
        $response['message'] = "Invalid request method";
    }

    // Clear any output buffer and send JSON
    ob_end_clean();
    echo json_encode($response);

} catch (Exception $e) {
    // Clear buffer and send error as JSON
    ob_end_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>
