<?php
require_once '../includes/config/db_config.php';
require_once '../includes/functions/auth.php';
require_once '../includes/functions/crud.php';
require_once '../includes/functions/upload.php';

requireLogin();

$success = '';
$error = '';

// Handle status change (approve/reject)
if (isset($_POST['change_status'])) {
    $id = filter_var($_POST['review_id'], FILTER_VALIDATE_INT);
    $status = $_POST['status'];
    
    if ($id && in_array($status, ['pending', 'approved', 'rejected'])) {
        if (update($pdo, 'reviews', ['status' => $status], $id)) {
            $success = "Review status updated to " . ucfirst($status);
        } else {
            $error = "Failed to update status";
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($id) {
        $review = readOne($pdo, 'reviews', $id);
        if ($review) {
            // Delete images
            deleteImage($review['client_photo_before'], '../public/images/uploads/');
            deleteImage($review['client_photo_after'], '../public/images/uploads/');
            
            if (delete($pdo, 'reviews', $id)) {
                $success = "Review deleted successfully";
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
    $status = $_POST['status'] ?? 'pending';
    
    if (!empty($clientName) && !empty($reviewText) && isset($_FILES['photo_before']) && isset($_FILES['photo_after'])) {
        // Upload before photo
        $beforeUpload = uploadImage($_FILES['photo_before'], '../public/images/uploads/');
        
        if ($beforeUpload['success']) {
            // Upload after photo
            $afterUpload = uploadImage($_FILES['photo_after'], '../public/images/uploads/');
            
            if ($afterUpload['success']) {
                $data = [
                    'client_name' => $clientName,
                    'client_photo_before' => $beforeUpload['filename'],
                    'client_photo_after' => $afterUpload['filename'],
                    'review_text' => $reviewText,
                    'status' => $status
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
$approvedCount = countRecords($pdo, 'reviews', 'status', 'approved');
$pendingCount = countRecords($pdo, 'reviews', 'status', 'pending');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews</title>
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
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .stat-card h3 { color: #667eea; font-size: 32px; }
        .stat-card p { color: #666; margin-top: 5px; }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
        input[type="text"], textarea, select, input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-warning {
            background: #ffc107;
            color: #333;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #667eea;
            color: white;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        .review-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Manage Reviews</h1>
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
            <div class="stat-card">
                <h3><?php echo $totalReviews; ?></h3>
                <p>Total Reviews</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $approvedCount; ?></h3>
                <p>Approved</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $pendingCount; ?></h3>
                <p>Pending</p>
            </div>
        </div>
        
        <div class="form-container">
            <h2>Add New Review</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="client_name">Client Name *</label>
                    <input type="text" id="client_name" name="client_name" required>
                </div>
                
                <div class="form-group">
                    <label for="photo_before">Before Photo *</label>
                    <input type="file" id="photo_before" name="photo_before" accept="image/*" required>
                </div>
                
                <div class="form-group">
                    <label for="photo_after">After Photo *</label>
                    <input type="file" id="photo_after" name="photo_after" accept="image/*" required>
                </div>
                
                <div class="form-group">
                    <label for="review_text">Review Text *</label>
                    <textarea id="review_text" name="review_text" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <button type="submit" name="add_review" class="btn btn-primary">Add Review</button>
            </form>
        </div>
        
        <div class="table-container">
            <h2 style="margin-bottom: 20px;">All Reviews</h2>
            
            <?php if (count($reviews) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client Name</th>
                            <th>Before</th>
                            <th>After</th>
                            <th>Review</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $review): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($review['id']); ?></td>
                                <td><?php echo htmlspecialchars($review['client_name']); ?></td>
                                <td>
                                    <img src="../public/images/uploads/<?php echo htmlspecialchars($review['client_photo_before']); ?>" 
                                         alt="Before" class="review-img">
                                </td>
                                <td>
                                    <img src="../public/images/uploads/<?php echo htmlspecialchars($review['client_photo_after']); ?>" 
                                         alt="After" class="review-img">
                                </td>
                                <td><?php echo htmlspecialchars(substr($review['review_text'], 0, 50)) . '...'; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $review['status']; ?>">
                                        <?php echo ucfirst($review['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" style="display: inline-block; margin-right: 5px;">
                                        <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                        <select name="status" style="width: auto; padding: 5px; margin-right: 5px;">
                                            <option value="pending" <?php echo $review['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="approved" <?php echo $review['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
                                            <option value="rejected" <?php echo $review['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                        </select>
                                        <button type="submit" name="change_status" class="btn btn-primary" style="padding: 5px 10px;">Update</button>
                                    </form>
                                    
                                    <a href="?delete=<?php echo $review['id']; ?>" 
                                       class="btn btn-danger" 
                                       style="padding: 5px 10px;"
                                       onclick="return confirm('Delete this review and its photos?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">No reviews found. Add your first review above.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
