<?php
function uploadImage($file, $targetDir = '../public/images/uploads/') {
    // Allowed MIME types
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    // Check if file was uploaded without errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload error occurred'];
    }
    
    // Validate file size
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'File size exceeds 5MB limit'];
    }
    
    // Validate MIME type using finfo (more secure than $_FILES['type'])
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, and GIF allowed'];
    }
    
    // Get file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($extension, $allowedExtensions)) {
        return ['success' => false, 'error' => 'Invalid file extension'];
    }
    
    // Create directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    // Generate unique filename to prevent overwriting
    $filename = uniqid('img_', true) . '.' . $extension;
    $targetPath = $targetDir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'filename' => $filename, 'path' => $targetPath];
    }
    
    return ['success' => false, 'error' => 'Failed to move uploaded file'];
}

// Function to delete image file
function deleteImage($filename, $targetDir = '../public/images/uploads/') {
    $filePath = $targetDir . $filename;
    if (file_exists($filePath)) {
        return unlink($filePath);
    }
    return false;
}

// Function to cleanup orphaned images in the uploads folder
function cleanupUploadsFolder($pdo, $targetDir = '../public/images/uploads/') {
    try {
        // Collect all used image filenames from all relevant tables
        $usedImages = [];
        
        // From gallery table
        $stmt = $pdo->query("SELECT image_path FROM gallery");
        $galleryImages = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if ($galleryImages) {
            $usedImages = array_merge($usedImages, $galleryImages);
        }
        
        // From reviews table (before and after photos)
        $stmt = $pdo->query("SELECT client_photo_before, client_photo_after FROM reviews");
        $reviewRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($reviewRows as $row) {
            if ($row['client_photo_before']) $usedImages[] = $row['client_photo_before'];
            if ($row['client_photo_after']) $usedImages[] = $row['client_photo_after'];
        }
        
        // Remove duplicates and empty values
        $usedImages = array_unique(array_filter($usedImages));
        
        // Get all files in the target directory
        $files = glob($targetDir . '*');
        
        $deletedCount = 0;
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    $filename = basename($file);
                    // If file is not in used images list, delete it
                    if (!in_array($filename, $usedImages)) {
                        unlink($file);
                        $deletedCount++;
                    }
                }
            }
        }
        return $deletedCount;
    } catch (Exception $e) {
        error_log("Cleanup error: " . $e->getMessage());
        return false;
    }
}
?>
