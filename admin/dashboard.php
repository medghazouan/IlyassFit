<?php
require_once '../includes/config/db_config.php';
require_once '../includes/functions/auth.php';
require_once '../includes/functions/crud.php';

requireLogin();

// Handle marking message as seen
if (isset($_GET['mark_seen'])) {
    $messageId = filter_var($_GET['mark_seen'], FILTER_VALIDATE_INT);
    if ($messageId) {
        update($pdo, 'messages', ['status' => 'seen'], $messageId);
        header("Location: manage_messages.php?view=" . $messageId);
        exit();
    }
}

// Get statistics
$totalMessages = countRecords($pdo, 'messages');
$totalReviews = countRecords($pdo, 'reviews');
$totalGallery = countRecords($pdo, 'gallery');
$unseenMessages = countRecords($pdo, 'messages', 'status', 'not seen');

// Get unseen messages
$stmt = $pdo->prepare("SELECT * FROM messages WHERE status = 'not seen' ORDER BY id DESC");
$stmt->execute();
$unseenMessagesList = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="apple-touch-icon" href="logo.png">
    <link rel="manifest" href="manifest.json">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <!-- Main Content Area -->
    <div class="main-content">
        <div class="top-bar">
            <h1>Dashboard</h1>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </div>
        </div>
        
        <div class="content-wrapper">
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon messages">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-info">
                        <h2><?php echo $totalMessages; ?></h2>
                        <p>Total Messages</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon reviews">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <h2><?php echo $totalReviews; ?></h2>
                        <p>Total Reviews</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon gallery">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="stat-info">
                        <h2><?php echo $totalGallery; ?></h2>
                        <p>Gallery Items</p>
                    </div>
                </div>
            </div>
            
            <!-- Unseen Messages Section -->
            <div class="messages-section">
                <div class="section-header">
                    <h2>Unseen Messages</h2>
                    <span class="badge"><?php echo $unseenMessages; ?> New</span>
                </div>
                
                <?php if (count($unseenMessagesList) > 0): ?>
                    <div class="messages-list">
                        <?php foreach ($unseenMessagesList as $msg): ?>
                            <div class="message-item">
                                <div class="message-header">
                                    <div class="sender-info">
                                        <i class="fas fa-user-circle"></i>
                                        <span class="sender-name"><?php echo htmlspecialchars($msg['full_name']); ?></span>
                                    </div>
                                    <span class="message-id">#<?php echo $msg['id']; ?></span>
                                </div>
                                <div class="message-details">
                                    <div class="detail-item">
                                        <i class="fas fa-envelope"></i>
                                        <span><?php echo htmlspecialchars($msg['email']); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-phone"></i>
                                        <span><?php echo htmlspecialchars($msg['telephone']); ?></span>
                                    </div>
                                </div>
                                <div class="message-content">
                                    <p><?php echo htmlspecialchars($msg['message']); ?></p>
                                </div>
                                <div class="message-actions">
                                    <a href="dashboard.php?mark_seen=<?php echo $msg['id']; ?>" class="btn-view">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-messages">
                        <i class="fas fa-check-circle"></i>
                        <p>No unseen messages. You're all caught up!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

<script>
// ============================================
// NOTIFICATION SYSTEM FOR NEW MESSAGES
// ============================================

let lastUnseenCount = <?php echo $unseenMessages; ?>;
let notificationPermission = Notification.permission;

// Register Service Worker for PWA
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('sw.js')
            .then(reg => {
                console.log('Service Worker registered');
            })
            .catch(err => console.log('Service Worker registration failed:', err));
    });
}

// Request notification permission
async function requestNotificationPermission() {
    if (!('Notification' in window)) {
        console.log('This browser does not support notifications');
        return false;
    }
    
    if (Notification.permission === 'granted') {
        return true;
    }
    
    if (Notification.permission !== 'denied') {
        const permission = await Notification.requestPermission();
        notificationPermission = permission;
        return permission === 'granted';
    }
    
    return false;
}

// Show notification
function showNotification(title, body, tag) {
    if (notificationPermission !== 'granted') return;
    
    const options = {
        body: body,
        icon: 'logo.png',
        badge: 'logo.png',
        tag: tag || 'ilyassfit-message',
        requireInteraction: true,
        vibrate: [200, 100, 200],
        data: {
            url: 'manage_messages.php'
        }
    };
    
    // Try to use service worker notification first (works even when minimized)
    if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
        navigator.serviceWorker.ready.then(registration => {
            registration.showNotification(title, options);
        });
    } else {
        // Fallback to regular notification
        const notification = new Notification(title, options);
        notification.onclick = function() {
            window.focus();
            window.location.href = 'manage_messages.php';
            notification.close();
        };
    }
}

// Check for new messages
async function checkForNewMessages() {
    try {
        const response = await fetch('check_messages.php');
        const data = await response.json();
        
        if (data.success && data.unseenCount > lastUnseenCount) {
            // New message received!
            const newCount = data.unseenCount - lastUnseenCount;
            const message = data.latestMessage;
            
            if (message) {
                const title = `${newCount} New Message${newCount > 1 ? 's' : ''}`;
                const body = `From: ${message.full_name}\n${message.message.substring(0, 100)}...`;
                showNotification(title, body, `msg-${message.id}`);
            }
            
            // Update the badge count on page
            updateBadgeCount(data.unseenCount);
        }
        
        lastUnseenCount = data.unseenCount;
        
    } catch (error) {
        console.log('Error checking messages:', error);
    }
}

// Update badge count in UI
function updateBadgeCount(count) {
    const badge = document.querySelector('.badge');
    if (badge) {
        badge.textContent = count + ' New';
    }
    
    // Update page title with count
    if (count > 0) {
        document.title = `(${count}) Admin Dashboard`;
    } else {
        document.title = 'Admin Dashboard';
    }
}

// Initialize notification system
document.addEventListener('DOMContentLoaded', async () => {
    // Request permission on first visit
    await requestNotificationPermission();
    
    // Check for new messages every 10 seconds (faster response)
    setInterval(checkForNewMessages, 10000);
    
    // Check immediately on page load
    checkForNewMessages();
});
</script>
</html>
