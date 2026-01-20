<?php
/**
 * Automatic Image Processor for Gallery
 * This file handles automatic image optimization when coach uploads photos
 */

class ImageProcessor {
    
    private $uploadDir;
    private $thumbDir;
    private $maxWidth = 1920;        // Max width for full images
    private $thumbWidth = 500;       // Width for thumbnails
    private $quality = 85;           // JPEG quality for full images
    private $thumbQuality = 80;      // JPEG quality for thumbnails
    
    public function __construct($uploadDir = 'images/uploads/', $thumbDir = 'images/uploads/thumbnails/') {
        $this->uploadDir = $uploadDir;
        $this->thumbDir = $thumbDir;
        
        // Create directories if they don't exist
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
        if (!file_exists($this->thumbDir)) {
            mkdir($this->thumbDir, 0777, true);
        }
    }
    
    /**
     * Process uploaded image - resize, optimize, create thumbnail
     * Returns: filename on success, false on failure
     */
    public function processUpload($uploadedFile) {
        // Validate upload
        if (!isset($uploadedFile['tmp_name']) || !is_uploaded_file($uploadedFile['tmp_name'])) {
            return false;
        }
        
        // Check file type
        $imageInfo = getimagesize($uploadedFile['tmp_name']);
        if ($imageInfo === false) {
            return false; // Not a valid image
        }
        
        $mimeType = $imageInfo['mime'];
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        
        if (!in_array($mimeType, $allowedTypes)) {
            return false;
        }
        
        // Generate unique filename
        $extension = $this->getExtensionFromMime($mimeType);
        $filename = uniqid('gallery_', true) . '_' . time() . '.' . $extension;
        
        $fullPath = $this->uploadDir . $filename;
        $thumbPath = $this->thumbDir . $filename;
        
        // Move uploaded file
        if (!move_uploaded_file($uploadedFile['tmp_name'], $fullPath)) {
            return false;
        }
        
        // Optimize the full image
        $this->optimizeImage($fullPath, $fullPath, $this->maxWidth, $this->quality);
        
        // Create thumbnail
        $this->createThumbnail($fullPath, $thumbPath, $this->thumbWidth, $this->thumbQuality);
        
        return $filename;
    }
    
    /**
     * Optimize existing image
     */
    public function optimizeImage($source, $destination, $maxWidth, $quality) {
        list($width, $height, $type) = getimagesize($source);
        
        // Only resize if image is larger than max width
        if ($width <= $maxWidth) {
            return true; // Already small enough
        }
        
        $ratio = $width / $height;
        $newWidth = $maxWidth;
        $newHeight = intval($maxWidth / $ratio);
        
        return $this->resizeImage($source, $destination, $newWidth, $newHeight, $type, $quality);
    }
    
    /**
     * Create thumbnail
     */
    public function createThumbnail($source, $destination, $maxWidth, $quality) {
        list($width, $height, $type) = getimagesize($source);
        
        $ratio = $width / $height;
        $newWidth = $maxWidth;
        $newHeight = intval($maxWidth / $ratio);
        
        return $this->resizeImage($source, $destination, $newWidth, $newHeight, $type, $quality);
    }
    
    /**
     * Core resize function
     */
    private function resizeImage($source, $destination, $newWidth, $newHeight, $type, $quality) {
        // Create new image canvas
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Load source image based on type
        switch ($type) {
            case IMAGETYPE_JPEG:
                $sourceImg = imagecreatefromjpeg($source);
                break;
            case IMAGETYPE_PNG:
                $sourceImg = imagecreatefrompng($source);
                // Preserve transparency for PNG
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
                imagefill($newImage, 0, 0, $transparent);
                break;
            case IMAGETYPE_GIF:
                $sourceImg = imagecreatefromgif($source);
                break;
            default:
                return false;
        }
        
        if (!$sourceImg) {
            return false;
        }
        
        // Get original dimensions
        list($origWidth, $origHeight) = getimagesize($source);
        
        // Resize image
        imagecopyresampled(
            $newImage, $sourceImg,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $origWidth, $origHeight
        );
        
        // Save optimized image
        $success = false;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $success = imagejpeg($newImage, $destination, $quality);
                break;
            case IMAGETYPE_PNG:
                // PNG quality is 0-9 (compression level)
                $pngQuality = intval((100 - $quality) / 11);
                $success = imagepng($newImage, $destination, $pngQuality);
                break;
            case IMAGETYPE_GIF:
                $success = imagegif($newImage, $destination);
                break;
        }
        
        // Clean up
        imagedestroy($newImage);
        imagedestroy($sourceImg);
        
        return $success;
    }
    
    /**
     * Get file extension from MIME type
     */
    private function getExtensionFromMime($mimeType) {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif'
        ];
        
        return $extensions[$mimeType] ?? 'jpg';
    }
    
    /**
     * Delete image and its thumbnail
     */
    public function deleteImage($filename) {
        $fullPath = $this->uploadDir . $filename;
        $thumbPath = $this->thumbDir . $filename;
        
        $success = true;
        
        if (file_exists($fullPath)) {
            $success = $success && unlink($fullPath);
        }
        
        if (file_exists($thumbPath)) {
            $success = $success && unlink($thumbPath);
        }
        
        return $success;
    }
}
?>