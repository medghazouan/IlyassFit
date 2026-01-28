<?php
require_once '../includes/config/db_config.php';
require_once '../includes/functions/auth.php';
require_once '../includes/functions/crud.php';
require_once '../includes/functions/upload.php';

requireLogin();

$success = '';
$error = '';
$editReview = null;

// Auto-cleanup orphaned files to ensure storage efficiency
cleanupUploadsFolder($pdo);

// Check for success message from redirect
if (isset($_GET['success']) && $_GET['success'] == 'updated') {
    $success = "Review updated successfully";
}

// Handle edit mode
if (isset($_GET['edit'])) {
    $id = filter_var($_GET['edit'], FILTER_VALIDATE_INT);
    if ($id) {
        $editReview = readOne($pdo, 'reviews', $id);
    }
}

// Handle update review
if (isset($_POST['update_review'])) {
    $id = filter_var($_POST['review_id'], FILTER_VALIDATE_INT);
    $clientName = trim($_POST['client_name']);
    $reviewText = trim($_POST['review_text']);
    
    if ($id && !empty($clientName) && !empty($reviewText)) {
        $review = readOne($pdo, 'reviews', $id);
        
        if ($review) {
            $data = [
                'client_name' => $clientName,
                'review_text' => $reviewText
            ];
            
            // Before photo
            if (isset($_FILES['photo_before']) && $_FILES['photo_before']['error'] !== UPLOAD_ERR_NO_FILE) {
                $beforeUpload = uploadImage($_FILES['photo_before'], '../images/uploads/');
                
                if ($beforeUpload['success']) {
                    deleteImage($review['client_photo_before'], '../images/uploads/');
                    $data['client_photo_before'] = $beforeUpload['filename'];
                } else {
                    $error = "Failed to upload before photo: " . $beforeUpload['error'];
                }
            }
            
            // After photo
            if (isset($_FILES['photo_after']) && $_FILES['photo_after']['error'] !== UPLOAD_ERR_NO_FILE) {
                $afterUpload = uploadImage($_FILES['photo_after'], '../public/uploads/');
                
                if ($afterUpload['success']) {
                    deleteImage($review['client_photo_after'], '../images/uploads/');
                    $data['client_photo_after'] = $afterUpload['filename'];
                } else {
                    $error = "Failed to upload after photo: " . $afterUpload['error'];
                }
            }
            
            if (empty($error) && update($pdo, 'reviews', $data, $id)) {
                cleanupUploadsFolder($pdo);
                header("Location: manage_reviews.php?success=updated");
                exit();
            } else if (empty($error)) {
                $error = "Failed to update review";
            }
        }
    } else {
        $error = "Please fill all required fields";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($id) {
        $review = readOne($pdo, 'reviews', $id);
        if ($review) {
            deleteImage($review['client_photo_before'], '../images/uploads/');
            deleteImage($review['client_photo_after'], '../images/uploads/');
            
            if (delete($pdo, 'reviews', $id)) {
                $success = "Review deleted successfully";
                cleanupUploadsFolder($pdo);
            } else {
                $error = "Failed to delete review";
            }
        }
    }
}

// Handle add new review
if (isset($_POST['add_review'])) {
    $clientName = trim($_POST['client_name']);
    $reviewText = trim($_POST['review_text']);
    
    if (!empty($clientName) && !empty($reviewText) && isset($_FILES['photo_before']) && isset($_FILES['photo_after'])) {
        $beforeUpload = uploadImage($_FILES['photo_before'], '../images/uploads/');
        
        if ($beforeUpload['success']) {
            $afterUpload = uploadImage($_FILES['photo_after'], '../images/uploads/');
            
            if ($afterUpload['success']) {
                $data = [
                    'client_name' => $clientName,
                    'client_photo_before' => $beforeUpload['filename'],
                    'client_photo_after' => $afterUpload['filename'],
                    'review_text' => $reviewText
                ];
                
                if (create($pdo, 'reviews', $data)) {
                    $success = "Review added successfully";
                } else {
                    $error = "Failed to add review";
                    deleteImage($beforeUpload['filename']);
                    deleteImage($afterUpload['filename']);
                }
            } else {
                $error = "Failed to upload after photo: " . $afterUpload['error'];
                deleteImage($beforeUpload['filename']);
            }
        } else {
            $error = "Failed to upload before photo: " . $beforeUpload['error'];
        }
    } else {
        $error = "Please fill all fields and upload both photos";
    }
}

// Get all reviews
$reviews = readAll($pdo, 'reviews', 'id', 'DESC');
$totalReviews = countRecords($pdo, 'reviews');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fc0404">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Manage Reviews</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="apple-touch-icon" href="logo.png">
    <link rel="manifest" href="manifest.json">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/manage_reviews.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <!-- Main Content Area -->
    <div class="main-content">
        <div class="top-bar">
            <h1>Manage Reviews</h1>
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
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $totalReviews; ?></h3>
                        <p>Total Reviews</p>
                    </div>
                </div>
            </div>
            
            <!-- Form Container -->
            <?php if ($editReview): ?>
            <!-- Edit Review Form -->
            <div class="form-container">
                <h2><i class="fas fa-edit"></i> Edit Review</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="review_id" value="<?php echo $editReview['id']; ?>">
                    
                    <div class="form-group">
                        <label for="client_name">Client Name *</label>
                        <input type="text" id="client_name" name="client_name" 
                               value="<?php echo htmlspecialchars($editReview['client_name']); ?>" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="photo_before">Before Photo</label>
                            <div class="current-image">
                                <p>Current:</p>
                                <?php 
                                $extBefore = strtolower(pathinfo($editReview['client_photo_before'], PATHINFO_EXTENSION));
                                if ($extBefore === 'webm'): 
                                ?>
                                    <video src="../images/uploads/<?php echo htmlspecialchars($editReview['client_photo_before']); ?>" 
                                           style="max-width: 150px;" autoplay loop muted playsinline></video>
                                <?php else: ?>
                                    <img src="../images/uploads/<?php echo htmlspecialchars($editReview['client_photo_before']); ?>" alt="Before">
                                <?php endif; ?>
                            </div>
                            <input type="file" id="photo_before" name="photo_before" accept="image/*,video/webm">
                            <p class="image-note">Leave empty to keep current photo</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="photo_after">After Photo</label>
                            <div class="current-image">
                                <p>Current:</p>
                                <?php 
                                $extAfter = strtolower(pathinfo($editReview['client_photo_after'], PATHINFO_EXTENSION));
                                if ($extAfter === 'webm'): 
                                ?>
                                    <video src="../images/uploads/<?php echo htmlspecialchars($editReview['client_photo_after']); ?>" 
                                           style="max-width: 150px;" autoplay loop muted playsinline></video>
                                <?php else: ?>
                                    <img src="../images/uploads/<?php echo htmlspecialchars($editReview['client_photo_after']); ?>" alt="After">
                                <?php endif; ?>
                            </div>
                            <input type="file" id="photo_after" name="photo_after" accept="image/*,video/webm">
                            <p class="image-note">Leave empty to keep current photo</p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="review_text">Review Text *</label>
                        <textarea id="review_text" name="review_text" required><?php echo htmlspecialchars($editReview['review_text']); ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update_review" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Review
                        </button>
                        <a href="manage_reviews.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
            <?php else: ?>
            <!-- Add New Review Form -->
            <div class="form-container">
                <h2><i class="fas fa-plus-circle"></i> Add New Review</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="client_name">Client Name *</label>
                        <input type="text" id="client_name" name="client_name" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="photo_before">Before Photo *</label>
                            <input type="file" id="photo_before" name="photo_before" accept="image/*,video/webm" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="photo_after">After Photo *</label>
                            <input type="file" id="photo_after" name="photo_after" accept="image/*,video/webm" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="review_text">Review Text *</label>
                        <textarea id="review_text" name="review_text" required></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="add_review" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Review
                        </button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
            
            <!-- Reviews Table -->
            <div class="table-container">
                <h2><i class="fas fa-list"></i> All Reviews</h2>
                
                <?php if (count($reviews) > 0): ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client Name</th>
                                    <th>Before</th>
                                    <th>After</th>
                                    <th>Review</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reviews as $review): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($review['id']); ?></td>
                                        <td><?php echo htmlspecialchars($review['client_name']); ?></td>
                                        <td>
                                            <?php 
                                            $extBefore = strtolower(pathinfo($review['client_photo_before'], PATHINFO_EXTENSION));
                                            if ($extBefore === 'webm'): 
                                            ?>
                                                <video src="../images/uploads/<?php echo htmlspecialchars($review['client_photo_before']); ?>" 
                                                       class="review-img" autoplay loop muted playsinline></video>
                                            <?php else: ?>
                                                <img src="../images/uploads/<?php echo htmlspecialchars($review['client_photo_before']); ?>" 
                                                     alt="Before" class="review-img">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $extAfter = strtolower(pathinfo($review['client_photo_after'], PATHINFO_EXTENSION));
                                            if ($extAfter === 'webm'): 
                                            ?>
                                                <video src="../images/uploads/<?php echo htmlspecialchars($review['client_photo_after']); ?>" 
                                                       class="review-img" autoplay loop muted playsinline></video>
                                            <?php else: ?>
                                                <img src="../images/uploads/<?php echo htmlspecialchars($review['client_photo_after']); ?>" 
                                                     alt="After" class="review-img">
                                            <?php endif; ?>
                                        </td>
                                        <td class="review-text"><?php echo htmlspecialchars(substr($review['review_text'], 0, 50)) . '...'; ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="?edit=<?php echo $review['id']; ?>" class="btn-action btn-edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?delete=<?php echo $review['id']; ?>" 
                                                   class="btn-action btn-delete" 
                                                   title="Delete"
                                                   onclick="return confirm('Delete this review and its photos?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-inbox"></i>
                        <p>No reviews found. Add your first review above.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
