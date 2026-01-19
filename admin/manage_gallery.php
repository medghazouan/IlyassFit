<?php
require_once '../includes/config/db_config.php';
require_once '../includes/functions/auth.php';
require_once '../includes/functions/crud.php';
require_once '../includes/functions/upload.php';

requireLogin();

$success = '';
$error = '';

const MAX_IMAGES = 12;

// Auto-cleanup orphaned files on page load to ensure storage efficiency
cleanupUploadsFolder($pdo);

// Handle delete
if (isset($_GET['delete'])) {
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($id) {
        $image = readOne($pdo, 'gallery', $id);
        if ($image) {
            // First delete the physical file
            deleteImage($image['image_path'], '../public/images/uploads/');
            
            // Then delete from database
            if (delete($pdo, 'gallery', $id)) {
                $success = "Image deleted successfully";
                // Cleanup after delete to be sure
                cleanupUploadsFolder($pdo);
            } else {
                $error = "Failed to delete image from database";
            }
        }
    }
}

// Handle single image upload
if (isset($_POST['upload_image']) && isset($_FILES['image'])) {
    $currentCount = countRecords($pdo, 'gallery');
    
    if ($currentCount >= MAX_IMAGES) {
        $error = "Gallery limit reached. Maximum " . MAX_IMAGES . " images allowed. Please delete an image before uploading a new one.";
    } else {
        $uploadResult = uploadImage($_FILES['image'], '../public/images/uploads/');
        
        if ($uploadResult['success']) {
            $data = [
                'image_path' => $uploadResult['filename']
            ];
            
            if (create($pdo, 'gallery', $data)) {
                $success = "Image uploaded successfully";
                cleanupUploadsFolder($pdo);
            } else {
                $error = "Failed to save image to database";
                deleteImage($uploadResult['filename'], '../public/images/uploads/');
            }
        } else {
            $error = "Upload failed: " . $uploadResult['error'];
        }
    }
}

// Handle multiple images upload
if (isset($_POST['upload_multiple']) && isset($_FILES['images'])) {
    $currentCount = countRecords($pdo, 'gallery');
    $remainingSpots = MAX_IMAGES - $currentCount;
    
    if ($remainingSpots <= 0) {
        $error = "Gallery limit reached. Maximum " . MAX_IMAGES . " images allowed.";
    } else {
        $uploadedCount = 0;
        $failedCount = 0;
        $limitReached = false;
        
        $totalFiles = count($_FILES['images']['name']);
        
        for ($i = 0; $i < $totalFiles; $i++) {
            if ($uploadedCount >= $remainingSpots) {
                $limitReached = true;
                break;
            }
            
            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $_FILES['images']['name'][$i],
                    'type' => $_FILES['images']['type'][$i],
                    'tmp_name' => $_FILES['images']['tmp_name'][$i],
                    'error' => $_FILES['images']['error'][$i],
                    'size' => $_FILES['images']['size'][$i]
                ];
                
                $uploadResult = uploadImage($file, '../public/images/uploads/');
                
                if ($uploadResult['success']) {
                    $data = ['image_path' => $uploadResult['filename']];
                    if (create($pdo, 'gallery', $data)) {
                        $uploadedCount++;
                    } else {
                        deleteImage($uploadResult['filename'], '../public/images/uploads/');
                        $failedCount++;
                    }
                } else {
                    $failedCount++;
                }
            }
        }
        
        if ($uploadedCount > 0) {
            $success = "$uploadedCount image(s) uploaded successfully.";
            cleanupUploadsFolder($pdo);
        }
        if ($limitReached) {
            $error = "Some images were not uploaded because the " . MAX_IMAGES . " image limit was reached.";
        } elseif ($failedCount > 0) {
            $error = "$failedCount image(s) failed to upload.";
        }
    }
}

// Get all gallery images
$images = readAll($pdo, 'gallery', 'id', 'DESC');
$totalImages = countRecords($pdo, 'gallery');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fc0404">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Manage Gallery</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="apple-touch-icon" href="logo.png">
    <link rel="manifest" href="manifest.json">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/manage_gallery.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <!-- Main Content Area -->
    <div class="main-content">
        <div class="top-bar">
            <h1>Manage Gallery</h1>
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
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $totalImages; ?> / <?php echo MAX_IMAGES; ?></h3>
                        <p>Total Images in Gallery</p>
                    </div>
                </div>
                <?php if ($totalImages >= MAX_IMAGES): ?>
                <div class="stat-card warning">
                    <div class="stat-icon" style="color: #ff9800;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Limit Reached</h3>
                        <p>You must delete an image to upload a new one.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Upload Forms -->
            <div class="upload-container">
                <div class="upload-section">
                    <h2><i class="fas fa-upload"></i> Upload Single Image</h2>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="image">Select Image</label>
                            <input type="file" id="image" name="image" accept="image/*,video/webm" required>
                        </div>
                        <button type="submit" name="upload_image" class="btn btn-primary" <?php echo ($totalImages >= MAX_IMAGES) ? 'disabled' : ''; ?>>
                            <i class="fas fa-cloud-upload-alt"></i> Upload Image
                        </button>
                    </form>
                </div>
                
                <div class="upload-section">
                    <h2><i class="fas fa-images"></i> Upload Multiple Images</h2>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="images">Select Multiple Images</label>
                            <input type="file" id="images" name="images[]" accept="image/*,video/webm" multiple required>
                            <p class="helper-text">
                                <i class="fas fa-info-circle"></i>
                                Hold Ctrl (Cmd on Mac) to select multiple images
                            </p>
                        </div>
                        <button type="submit" name="upload_multiple" class="btn btn-primary" <?php echo ($totalImages >= MAX_IMAGES) ? 'disabled' : ''; ?>>
                            <i class="fas fa-cloud-upload-alt"></i> Upload Multiple Images
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Gallery Section -->
            <div class="gallery-section">
                <div class="section-header">
                    <h2><i class="fas fa-th"></i> Gallery Images</h2>
                </div>
                
                <?php if (count($images) > 0): ?>
                    <div class="gallery-grid">
                        <?php foreach ($images as $img): ?>
                            <div class="gallery-item">
                                <?php 
                                $ext = strtolower(pathinfo($img['image_path'], PATHINFO_EXTENSION));
                                if ($ext === 'webm'): 
                                ?>
                                    <video src="../public/images/uploads/<?php echo htmlspecialchars($img['image_path']); ?>" 
                                           class="gallery-preview-video" autoplay loop muted playsinline
                                           style="width: 100%; height: 100%; object-fit: cover;">
                                    </video>
                                <?php else: ?>
                                    <img src="../public/images/uploads/<?php echo htmlspecialchars($img['image_path']); ?>" 
                                         alt="Gallery Image">
                                <?php endif; ?>
                                <div class="gallery-overlay">
                                    <div class="image-id">
                                        <i class="fas fa-hashtag"></i>
                                        <?php echo $img['id']; ?>
                                    </div>
                                    <a href="?delete=<?php echo $img['id']; ?>" 
                                       class="btn-delete" 
                                       onclick="return confirm('Delete this image?')">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-images"></i>
                        <p>No images in gallery yet. Upload your first image above.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
