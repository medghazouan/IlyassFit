<?php
require_once '../includes/config/db_config.php';
require_once '../includes/functions/auth.php';
require_once '../includes/functions/crud.php';
require_once '../includes/functions/upload.php';

requireLogin();

$success = '';
$error = '';

// Handle delete
if (isset($_GET['delete'])) {
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($id) {
        $image = readOne($pdo, 'gallery', $id);
        if ($image) {
            // Delete image file
            deleteImage($image['image_path'], '../public/images/uploads/');
            
            if (delete($pdo, 'gallery', $id)) {
                $success = "Image deleted successfully";
            } else {
                $error = "Failed to delete image";
            }
        }
    }
}

// Handle single image upload
if (isset($_POST['upload_image']) && isset($_FILES['image'])) {
    $uploadResult = uploadImage($_FILES['image'], '../public/images/uploads/');
    
    if ($uploadResult['success']) {
        $data = [
            'image_path' => $uploadResult['filename']
        ];
        
        if (create($pdo, 'gallery', $data)) {
            $success = "Image uploaded successfully";
        } else {
            $error = "Failed to save image to database";
            deleteImage($uploadResult['filename']);
        }
    } else {
        $error = "Upload failed: " . $uploadResult['error'];
    }
}

// Handle multiple images upload
if (isset($_POST['upload_multiple']) && isset($_FILES['images'])) {
    $uploadedCount = 0;
    $failedCount = 0;
    
    $totalFiles = count($_FILES['images']['name']);
    
    for ($i = 0; $i < $totalFiles; $i++) {
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
                    deleteImage($uploadResult['filename']);
                    $failedCount++;
                }
            } else {
                $failedCount++;
            }
        }
    }
    
    if ($uploadedCount > 0) {
        $success = "$uploadedCount image(s) uploaded successfully";
    }
    if ($failedCount > 0) {
        $error = "$failedCount image(s) failed to upload";
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
    <title>Manage Gallery</title>
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
            margin-left: 10px;
        }
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .stats {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .stats h3 { color: #667eea; font-size: 32px; }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .upload-section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid #eee;
        }
        .upload-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px dashed #667eea;
            border-radius: 5px;
            background: #f8f9ff;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .gallery-item {
            position: relative;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .gallery-item:hover {
            transform: translateY(-5px);
        }
        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
        }
        .gallery-item-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            padding: 15px;
            transform: translateY(100%);
            transition: transform 0.3s;
        }
        .gallery-item:hover .gallery-item-overlay {
            transform: translateY(0);
        }
        .gallery-item-id {
            color: white;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #666;
            background: white;
            border-radius: 10px;
        }
        .no-data h3 {
            margin-bottom: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Manage Gallery</h1>
        <div>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="stats">
            <h3><?php echo $totalImages; ?></h3>
            <p>Total Images in Gallery</p>
        </div>
        
        <div class="form-container">
            <div class="upload-section">
                <h2>Upload Single Image</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="image">Select Image</label>
                        <input type="file" id="image" name="image" accept="image/*" required>
                    </div>
                    <button type="submit" name="upload_image" class="btn btn-primary">Upload Image</button>
                </form>
            </div>
            
            <div class="upload-section">
                <h2>Upload Multiple Images</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="images">Select Multiple Images</label>
                        <input type="file" id="images" name="images[]" accept="image/*" multiple required>
                        <small style="color: #666; display: block; margin-top: 5px;">Hold Ctrl (Cmd on Mac) to select multiple images</small>
                    </div>
                    <button type="submit" name="upload_multiple" class="btn btn-primary">Upload Multiple Images</button>
                </form>
            </div>
        </div>
        
        <h2 style="margin-bottom: 20px;">Gallery Images</h2>
        
        <?php if (count($images) > 0): ?>
            <div class="gallery-grid">
                <?php foreach ($images as $img): ?>
                    <div class="gallery-item">
                        <img src="../public/images/uploads/<?php echo htmlspecialchars($img['image_path']); ?>" 
                             alt="Gallery Image">
                        <div class="gallery-item-overlay">
                            <div class="gallery-item-id">ID: <?php echo $img['id']; ?></div>
                            <a href="?delete=<?php echo $img['id']; ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Delete this image?')"
                               style="width: 100%; text-align: center; text-decoration: none;">
                                Delete Image
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-data">
                <h3>📸 No Images Yet</h3>
                <p>Upload your first image using the form above</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
