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

    $response = ['success' => false, 'message' => ''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                $response['success'] = true;
                $response['message'] = "Message sent successfully! We'll get back to you soon.";
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
