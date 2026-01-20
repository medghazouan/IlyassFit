<?php
require_once '../includes/config/db_config.php';
require_once '../includes/functions/auth.php';
require_once '../includes/functions/crud.php';
require_once '../includes/functions/upload.php';
require_once 'image_processor.php'; // NEW: Add image processor

requireLogin();

$success = '';
$error = '';

const MAX_IMAGES = 12;

// Initialize image processor
$imageProcessor = new ImageProcessor('../public/images/uploads/', '../public/images/uploads/thumbnails/');

// Auto-cleanup orphaned files on page load to ensure storage efficiency
cleanupUploadsFolder($pdo);

// Handle delete
if (isset($_GET['delete'])) {
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($id) {
        $image = readOne($pdo, 'gallery', $id);
        if ($image) {
            // Delete both original and thumbnail using image processor
            $imageProcessor->deleteImage($image['image_path']);
            
            // Then delete from database
            if (delete($pdo, 'gallery', $id)) {
                $success = "Image deleted successfully";
                cleanupUploadsFolder($pdo);
            } else {
                $error = "Failed to delete image from database";
            }
        }
    }
}

// Handle single image upload with AUTOMATIC OPTIMIZATION
if (isset($_POST['upload_image']) && isset($_FILES['image'])) {
    $currentCount = countRecords($pdo, 'gallery');
    
    if ($currentCount >= MAX_IMAGES) {
        $error = "Gallery limit reached. Maximum " . MAX_IMAGES . " images allowed. Please delete an image before uploading a new one.";
    } else {
        // NEW: Use image processor for automatic optimization
        $filename = $imageProcessor->processUpload($_FILES['image']);
        
        if ($filename !== false) {
            $data = ['image_path' => $filename];
            
            if (create($pdo, 'gallery', $data)) {
                $success = "Image uploaded and optimized successfully! (Original resized + thumbnail created)";
                cleanupUploadsFolder($pdo);
            } else {
                $error = "Failed to save image to database";
                $imageProcessor->deleteImage($filename);
            }
        } else {
            $error = "Upload failed: Please upload a valid JPG, PNG, or GIF image.";
        }
    }
}

// Handle multiple images upload with AUTOMATIC OPTIMIZATION
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
                
                // NEW: Use image processor for automatic optimization
                $filename = $imageProcessor->processUpload($file);
                
                if ($filename !== false) {
                    $data = ['image_path' => $filename];
                    if (create($pdo, 'gallery', $data)) {
                        $uploadedCount++;
                    } else {
                        $imageProcessor->deleteImage($filename);
                        $failedCount++;
                    }
                } else {
                    $failedCount++;
                }
            }
        }
        
        if ($uploadedCount > 0) {
            $success = "$uploadedCount image(s) uploaded and optimized successfully! (Resized + thumbnails created)";
            cleanupUploadsFolder($pdo);
        }
        if ($limitReached) {
            $error = "Some images were not uploaded because the " . MAX_IMAGES . " image limit was reached.";
        } elseif ($failedCount > 0) {
            $error .= " $failedCount image(s) failed to upload.";
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
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="stat-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Auto-Optimized</h3>
                        <p>Images automatically resized & compressed</p>
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
                    <p class="optimization-notice">
                        <i class="fas fa-magic"></i> 
                        Images are automatically optimized for web (resized to 1920px max, compressed, thumbnail created)
                    </p>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="image">Select Image</label>
                            <input type="file" id="image" name="image" accept="image/jpeg,image/jpg,image/png,image/gif" required>
                        </div>
                        <button type="submit" name="upload_image" class="btn btn-primary" <?php echo ($totalImages >= MAX_IMAGES) ? 'disabled' : ''; ?>>
                            <i class="fas fa-cloud-upload-alt"></i> Upload & Optimize Image
                        </button>
                    </form>
                </div>
                
                <div class="upload-section">
                    <h2><i class="fas fa-images"></i> Upload Multiple Images</h2>
                    <p class="optimization-notice">
                        <i class="fas fa-magic"></i> 
                        All images automatically optimized for fast loading
                    </p>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="images">Select Multiple Images</label>
                            <input type="file" id="images" name="images[]" accept="image/jpeg,image/jpg,image/png,image/gif" multiple required>
                            <p class="helper-text">
                                <i class="fas fa-info-circle"></i>
                                Hold Ctrl (Cmd on Mac) to select multiple images
                            </p>
                        </div>
                        <button type="submit" name="upload_multiple" class="btn btn-primary" <?php echo ($totalImages >= MAX_IMAGES) ? 'disabled' : ''; ?>>
                            <i class="fas fa-cloud-upload-alt"></i> Upload & Optimize Multiple Images
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Gallery Section -->
            <div class="gallery-section">
                <div class="section-header">
                    <h2><i class="fas fa-th"></i> Gallery Images</h2>
                    <p class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Showing optimized thumbnails (full images load on website when clicked)
                    </p>
                </div>
                
                <?php if (count($images) > 0): ?>
                    <div class="gallery-grid">
                        <?php foreach ($images as $img): ?>
                            <div class="gallery-item">
                                <?php 
                                $ext = strtolower(pathinfo($img['image_path'], PATHINFO_EXTENSION));
                                // Check if thumbnail exists, otherwise use original
                                $thumbPath = '../public/images/uploads/thumbnails/' . $img['image_path'];
                                $imagePath = file_exists($thumbPath) ? $thumbPath : '../public/images/uploads/' . $img['image_path'];
                                
                                if ($ext === 'webm'): 
                                ?>
                                    <video src="<?php echo htmlspecialchars($imagePath); ?>" 
                                           class="gallery-preview-video" autoplay loop muted playsinline
                                           style="width: 100%; height: 100%; object-fit: cover;">
                                    </video>
                                <?php else: ?>
                                    <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                         alt="Gallery Image"
                                         loading="lazy">
                                <?php endif; ?>
                                <div class="gallery-overlay">
                                    <div class="image-id">
                                        <i class="fas fa-hashtag"></i>
                                        <?php echo $img['id']; ?>
                                    </div>
                                    <?php if (file_exists($thumbPath)): ?>
                                        <div class="optimized-badge">
                                            <i class="fas fa-check-circle"></i> Optimized
                                        </div>
                                    <?php endif; ?>
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
    
    <style>
        .optimization-notice {
            background: rgba(102, 126, 234, 0.1);
            border-left: 3px solid #667eea;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 0.9rem;
            color: #667eea;
        }
        
        .optimization-notice i {
            margin-right: 8px;
        }
        
        .optimized-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(76, 175, 80, 0.9);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .optimized-badge i {
            margin-right: 5px;
        }
        
        .text-muted {
            color: #888;
            font-size: 0.9rem;
            margin-top: 10px;
        }
    </style>
</body>
</html>