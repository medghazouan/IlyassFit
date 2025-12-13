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
?>
