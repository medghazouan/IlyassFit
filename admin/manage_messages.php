<?php
require_once '../includes/config/db_config.php';
require_once '../includes/functions/auth.php';
require_once '../includes/functions/crud.php';

requireLogin();

$success = '';
$error = '';

// Handle delete action
if (isset($_GET['delete'])) {
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($id && delete($pdo, 'messages', $id)) {
        $success = "Message deleted successfully";
    } else {
        $error = "Failed to delete message";
    }
}

// Get all messages
$messages = readAll($pdo, 'messages', 'id', 'DESC');
$totalMessages = countRecords($pdo, 'messages');
$seenCount = countRecords($pdo, 'messages', 'status', 'seen');
$notSeenCount = countRecords($pdo, 'messages', 'status', 'not seen');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Messages</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/manage_messages.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <!-- Main Content Area -->
    <div class="main-content">
        <div class="top-bar">
            <h1>Manage Messages</h1>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </div>
        </div>
        
        <div class="content-wrapper">
            <!-- Alerts -->
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
            
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $totalMessages; ?></h3>
                        <p>Total Messages</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon seen">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $seenCount; ?></h3>
                        <p>Seen</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon unseen">
                        <i class="fas fa-envelope-open"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $notSeenCount; ?></h3>
                        <p>Not Seen</p>
                    </div>
                </div>
            </div>
            
            <!-- Messages Section -->
            <div class="messages-section">
                <div class="section-header">
                    <h2><i class="fas fa-inbox"></i> Client Messages</h2>
                </div>
                
                <?php if (count($messages) > 0): ?>
                    <div class="messages-grid">
                        <?php foreach ($messages as $msg): ?>
                            <div class="message-card">
                                <div class="message-header">
                                    <div class="sender-info">
                                        <div class="avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="sender-details">
                                            <h4><?php echo htmlspecialchars($msg['full_name']); ?></h4>
                                            <span class="message-id">#<?php echo $msg['id']; ?></span>
                                        </div>
                                    </div>
                                    <span class="status-badge status-<?php echo str_replace(' ', '-', $msg['status']); ?>">
                                        <i class="fas fa-circle"></i>
                                        <?php echo ucfirst($msg['status']); ?>
                                    </span>
                                </div>
                                
                                <div class="contact-info">
                                    <div class="contact-item">
                                        <i class="fas fa-envelope"></i>
                                        <span><?php echo htmlspecialchars($msg['email']); ?></span>
                                    </div>
                                    <div class="contact-item">
                                        <i class="fas fa-phone"></i>
                                        <span><?php echo htmlspecialchars($msg['telephone']); ?></span>
                                    </div>
                                </div>
                                
                                <div class="message-content">
                                    <p><?php echo htmlspecialchars($msg['message']); ?></p>
                                </div>
                                
                                <div class="message-actions">
                                    <a href="?delete=<?php echo $msg['id']; ?>" 
                                       class="btn btn-delete" 
                                       onclick="return confirm('Are you sure you want to delete this message?')">
                                        <i class="fas fa-trash"></i> Delete Message
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-inbox"></i>
                        <p>No messages found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
