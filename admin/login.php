<?php
require_once '../includes/config/db_config.php';
require_once '../includes/functions/auth.php';

startSecureSession();

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Invalid username or password";
            }
        } catch (PDOException $e) {
            $error = "Login failed. Please try again.";
            error_log("Login error: " . $e->getMessage());
        }
    } else {
        $error = "Please fill in all fields";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-red: #fc0404;
            --dark-bg: #1b1f22;
            --dark-secondary: #212529;
            --white: #ffffff;
            --gray: #b0b0b0;
        }

        html {
            height: 100%;
            overflow: hidden;
            /* Prevent scrolling on html */
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1b1f22 0%, #2d3436 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            height: 100vh;
            /* Fixed height */
            position: fixed;
            /* Changed from relative to fixed */
            width: 100%;
            /* Full width */
            overflow: hidden;
            /* Prevent scrolling */
            top: 0;
            left: 0;
        }

        /* Animated background */
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(252, 4, 4, 0.1) 0%, transparent 70%);
            top: -250px;
            right: -250px;
            animation: float 6s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(252, 4, 4, 0.08) 0%, transparent 70%);
            bottom: -200px;
            left: -200px;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .login-container {
            background: var(--dark-secondary);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            max-height: 90vh;
            /* Prevent container from being too tall */
            overflow-y: auto;
            /* Allow scrolling inside container if needed */
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Hide scrollbar but keep functionality */
        .login-container::-webkit-scrollbar {
            width: 0;
            background: transparent;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 140px;
            height: auto;
            filter: drop-shadow(0 4px 8px rgba(252, 4, 4, 0.3));
        }

        h2 {
            text-align: center;
            color: var(--white);
            margin-bottom: 6px;
            font-size: 24px;
        }

        .subtitle {
            text-align: center;
            color: var(--gray);
            margin-bottom: 25px;
            font-size: 13px;
        }

        .error {
            background: rgba(252, 4, 4, 0.2);
            color: var(--primary-red);
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border-left: 4px solid var(--primary-red);
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .error i {
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: var(--white);
            font-weight: 600;
            font-size: 13px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 15px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            font-size: 14px;
            background: var(--dark-bg);
            color: var(--white);
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(252, 4, 4, 0.1);
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: var(--gray);
        }

        button {
            width: 100%;
            padding: 12px;
            background: var(--primary-red);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 5px;
        }

        button:hover {
            background: #d00303;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(252, 4, 4, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        .footer-text {
            text-align: center;
            color: var(--gray);
            margin-top: 20px;
            font-size: 12px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 20px 18px;
                margin: 10px;
                max-height: 95vh;
                /* More height on small screens */
            }

            .logo-container {
                margin-bottom: 15px;
            }

            .logo-container img {
                max-width: 100px;
            }

            h2 {
                font-size: 20px;
                margin-bottom: 5px;
            }

            .subtitle {
                font-size: 12px;
                margin-bottom: 20px;
            }

            .form-group {
                margin-bottom: 15px;
            }

            label {
                font-size: 12px;
                margin-bottom: 5px;
            }

            input[type="text"],
            input[type="password"] {
                padding: 10px 12px 10px 38px;
                font-size: 13px;
            }

            .input-wrapper i {
                left: 12px;
                font-size: 14px;
            }

            button {
                padding: 11px;
                font-size: 14px;
            }

            .footer-text {
                margin-top: 15px;
                font-size: 11px;
            }

            .error {
                padding: 8px 10px;
                font-size: 12px;
                margin-bottom: 15px;
            }
        }

        @media (max-width: 360px) {
            .login-container {
                padding: 18px 15px;
            }

            .logo-container img {
                max-width: 90px;
            }

            h2 {
                font-size: 18px;
            }
        }
    </style>
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

</html>