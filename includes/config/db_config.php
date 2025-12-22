<?php
// Database configuration
// Detect environment
$isLocal = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1');

if ($isLocal) {
    // Localhost (XAMPP) Credentials
    $host = 'localhost';
    $db = 'ilyassfitdb'; 
    $user = 'root';
    $pass = '';
} else {
    // Production (Hostinger) Credentials - UPDATE THESE BEFORE DEPLOYING
    $host = 'localhost';
    $db = 'u123456789_ilyassfit'; // Example: u123456789_dbname
    $user = 'u123456789_admin';   // Example: u123456789_username
    $pass = 'YourStrongPassword123!'; 
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
