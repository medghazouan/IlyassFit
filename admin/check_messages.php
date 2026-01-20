<?php
/**
 * API endpoint to check for unseen messages
 * Used by the notification polling system
 */

require_once '../includes/config/db_config.php';
require_once '../includes/functions/auth.php';
require_once '../includes/functions/crud.php';

// Set JSON header
header('Content-Type: application/json');

// Check if user is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    // Get count of unseen messages
    $unseenCount = countRecords($pdo, 'messages', 'status', 'not seen');
    
    // Get the latest unseen message for notification content
    $latestMessage = null;
    if ($unseenCount > 0) {
        $stmt = $pdo->prepare("SELECT id, full_name, message FROM messages WHERE status = 'not seen' ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $latestMessage = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    echo json_encode([
        'success' => true,
        'unseenCount' => $unseenCount,
        'latestMessage' => $latestMessage
    ]);
    
} catch (PDOException $e) {
    error_log("Check messages error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
?>
