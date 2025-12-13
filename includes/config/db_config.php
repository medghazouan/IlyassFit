<?php
// Database configuration
$host = 'localhost';
$db = 'ilyassfitdb';  // Change to your database name
$user = 'root';           // Change to your MySQL username
$pass = '';               // Change to your MySQL password
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
    // Log error instead of displaying in production
    error_log("Database connection failed: " . $e->getMessage());
    die("Connection failed. Please try again later.");
}
?>
