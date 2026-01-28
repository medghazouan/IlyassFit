<?php
require_once '../includes/config/db_config.php';
require_once '../includes/functions/auth.php';
require_once '../includes/functions/security.php';

startSecureSession();

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check rate limit first (5 attempts / 15 minutes)
    if (isRateLimited('login', 5, 900)) {
        $remainingTime = getRateLimitResetTime('login', 900);
        $error = formatRateLimitMessage($remainingTime);
    }
    // Validate CSRF token
    elseif (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid security token. Please refresh the page and try again.";
        incrementRateLimit('login'); // Count failed CSRF as attempt
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!empty($username) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                // Regenerate session ID to prevent session fixation
                regenerateSession();

                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                
                // Reset rate limit on successful login
                resetRateLimit('login');

                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Invalid username or password";
                incrementRateLimit('login'); // Increment on failed login
            }
        } catch (PDOException $e) {
            $error = "Login failed. Please try again.";
            error_log("Login error: " . $e->getMessage());
            incrementRateLimit('login'); // Increment on error
        }
        } else {
            $error = "Please fill in all fields";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fc0404">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="IF Admin">
    <title>Admin Login</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="apple-touch-icon" href="logo.png">
    <link rel="manifest" href="manifest.json">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="logo.png" alt="Logo">
        </div>

        <h2>Admin Login</h2>
        <p class="subtitle">Sign in to access your dashboard</p>

        <?php if ($error): ?>
            <div class="error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <?php echo csrfTokenField(); ?>
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required
                        autofocus>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>

            <button type="submit">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </button>
        </form>

        <p class="footer-text">© <?php echo date('Y'); ?> Ilyass Fit. All rights reserved.</p>
    </div>
</body>

<script>
// Register Service Worker for PWA
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('sw.js')
            .then(reg => console.log('Service Worker registered'))
            .catch(err => console.log('Service Worker registration failed:', err));
    });
}
</script>
</html>