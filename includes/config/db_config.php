<?php
// Database configuration
// Detect environment
$isLocal = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1');

if ($isLocal) {
    // Localhost (XAMPP) Credentials - for local testing
    $host = 'localhost';
    $db = 'ilyassfitdb'; 
    $user = 'root';
    $pass = '';
} else {
    // ============================================
    // HOSTINGER PRODUCTION CREDENTIALS
    // ============================================
    $host = 'localhost';
    $db = 'u974444073_ilyassfit';
    $user = 'u974444073_admin';
    $pass = '5f7Lma|BAV]';
}

$charset = 'utf8mb4';

// PDO options for better security and error handling
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Establish PDO connection
try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Log error securely
    error_log("Database connection failed: " . $e->getMessage());
    // Generic error message for user
    die("Service temporarily unavailable. Please try again later.");
}
?>
