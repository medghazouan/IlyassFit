<?php
require_once __DIR__ . '/../includes/functions/auth.php';

startSecureSession();

header('Content-Type: application/json');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$tabId = $data['tab_id'] ?? null;

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

if (!$tabId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing tab_id']);
    exit;
}

// If no tab_id set in session, bind this tab
if (!isset($_SESSION['tab_id'])) {
    $_SESSION['tab_id'] = $tabId;
    echo json_encode(['status' => 'bound']);
    exit;
}

// If tab ids match, ok
if (isset($_SESSION['tab_id']) && $_SESSION['tab_id'] === $tabId) {
    echo json_encode(['status' => 'ok']);
    exit;
}

// Tab id mismatch -> destroy session and require login
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}
session_destroy();

http_response_code(401);
echo json_encode(['error' => 'Session invalid']);
exit;

?>
