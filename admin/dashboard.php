<?php
require_once '../includes/functions/auth.php';

// Protect this page - redirect to login if not authenticated
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .navbar {
            background: #333;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 { font-size: 24px; }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            background: #667eea;
            border-radius: 5px;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .welcome {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .menu-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .menu-card:hover { transform: translateY(-5px); }
        .menu-card a {
            text-decoration: none;
            color: #333;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Admin Dashboard</h1>
        <div>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome">
            <h2>Welcome to Admin Panel</h2>
            <p>Manage your bodybuilding website content from here.</p>
        </div>
        
        <div class="menu-grid">
            <div class="menu-card">
                <a href="manage_reviews.php">📝 Manage Reviews</a>
            </div>
            <div class="menu-card">
                <a href="manage_gallery.php">🖼️ Manage Gallery</a>
            </div>
            <div class="menu-card">
                <a href="manage_messages.php">💬 View Messages</a>
            </div>
            <div class="menu-card">
                <a href="manage_pricing.php">💰 Manage Pricing</a>
            </div>
        </div>
    </div>
</body>
</html>
