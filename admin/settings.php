<?php
require_once '../includes/config/db_config.php';
require_once '../includes/functions/auth.php';
require_once '../includes/functions/security.php';

requireLogin();

$success = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid security token. Please refresh and try again.";
    } else {
        $action = $_POST['action'] ?? '';

        // Change Password
        if ($action === 'change_password') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $error = "All password fields are required.";
            } elseif ($newPassword !== $confirmPassword) {
                $error = "New passwords do not match.";
            } elseif (strlen($newPassword) < 8) {
                $error = "Password must be at least 8 characters.";
            } else {
                // Verify current password
                $stmt = $pdo->prepare("SELECT password FROM admin WHERE id = ?");
                $stmt->execute([$_SESSION['admin_id']]);
                $admin = $stmt->fetch();

                if ($admin && password_verify($currentPassword, $admin['password'])) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE id = ?");
                    $stmt->execute([$hashedPassword, $_SESSION['admin_id']]);
                    $success = "Password changed successfully!";
                } else {
                    $error = "Current password is incorrect.";
                }
            }
        }

        // Add New Admin
        elseif ($action === 'add_admin') {
            $newUsername = trim($_POST['new_username'] ?? '');
            $newAdminPassword = $_POST['new_admin_password'] ?? '';

            if (empty($newUsername) || empty($newAdminPassword)) {
                $error = "Username and password are required.";
            } elseif (strlen($newAdminPassword) < 8) {
                $error = "Password must be at least 8 characters.";
            } else {
                // Check if username already exists
                $stmt = $pdo->prepare("SELECT id FROM admin WHERE username = ?");
                $stmt->execute([$newUsername]);
                if ($stmt->fetch()) {
                    $error = "Username already exists.";
                } else {
                    $hashedPassword = password_hash($newAdminPassword, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
                    $stmt->execute([$newUsername, $hashedPassword]);
                    $success = "Admin '$newUsername' added successfully!";
                }
            }
        }

        // Remove Admin
        elseif ($action === 'remove_admin') {
            $adminIdToRemove = filter_var($_POST['admin_id'] ?? '', FILTER_VALIDATE_INT);

            if (!$adminIdToRemove) {
                $error = "Invalid admin selected.";
            } elseif ($adminIdToRemove == $_SESSION['admin_id']) {
                $error = "You cannot remove yourself.";
            } else {
                $stmt = $pdo->prepare("DELETE FROM admin WHERE id = ?");
                $stmt->execute([$adminIdToRemove]);
                $success = "Admin removed successfully!";
            }
        }
    }
}

// Get all admins
$stmt = $pdo->query("SELECT id, username FROM admin ORDER BY id ASC");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fc0404">
    <title>Settings - Admin Dashboard</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/settings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="main-content">
        <div class="top-bar">
            <h1>Settings</h1>
            <div class="user-info">
                <span>Logged in as: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </div>
        </div>
        
        <div class="content-wrapper">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="settings-grid">
                <!-- Change Password Section -->
                <div class="settings-card">
                    <div class="card-header">
                        <i class="fas fa-key"></i>
                        <h2>Change Password</h2>
                    </div>
                    <form method="POST" class="settings-form">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                        <input type="hidden" name="action" value="change_password">
                        
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" required minlength="8">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Password
                        </button>
                    </form>
                </div>

                <!-- Add New Admin Section -->
                <div class="settings-card">
                    <div class="card-header">
                        <i class="fas fa-user-plus"></i>
                        <h2>Add New Admin</h2>
                    </div>
                    <form method="POST" class="settings-form">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                        <input type="hidden" name="action" value="add_admin">
                        
                        <div class="form-group">
                            <label for="new_username">Username</label>
                            <input type="text" id="new_username" name="new_username" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_admin_password">Password</label>
                            <input type="password" id="new_admin_password" name="new_admin_password" required minlength="8">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Admin
                        </button>
                    </form>
                </div>

                <!-- Manage Admins Section -->
                <div class="settings-card full-width">
                    <div class="card-header">
                        <i class="fas fa-users-cog"></i>
                        <h2>Manage Admins</h2>
                    </div>
                    <div class="admins-list">
                        <table class="admins-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($admins as $admin): ?>
                                    <tr>
                                        <td><?php echo $admin['id']; ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($admin['username']); ?>
                                            <?php if ($admin['id'] == $_SESSION['admin_id']): ?>
                                                <span class="badge-you">You</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                                <form method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to remove this admin?');">
                                                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                                    <input type="hidden" name="action" value="remove_admin">
                                                    <input type="hidden" name="admin_id" value="<?php echo $admin['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted">Cannot remove yourself</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
