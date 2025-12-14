<?php
require_once '../includes/config/db_config.php';
require_once '../includes/functions/auth.php';
require_once '../includes/functions/crud.php';

requireLogin();

$success = '';
$error = '';

// Handle add pricing plan
if (isset($_POST['add_plan'])) {
    $planName = trim($_POST['plan_name']);
    $coachingType = $_POST['coaching_type'];
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $duration = trim($_POST['duration']);
    $bookingUrl = trim($_POST['booking_url']);
    $buttonText = trim($_POST['button_text']);
    $description = trim($_POST['description']);
    $features = trim($_POST['features']);
    $displayOrder = filter_var($_POST['display_order'], FILTER_VALIDATE_INT);
    
    if (!empty($planName) && $price !== false) {
        $data = [
            'plan_name' => $planName,
            'coaching_type' => $coachingType,
            'price' => $price,
            'duration' => $duration,
            'booking_url' => $bookingUrl,
            'button_text' => $buttonText,
            'description' => $description,
            'features' => $features,
            'display_order' => $displayOrder,
            'is_active' => 1
        ];
        
        if (create($pdo, 'pricing_plans', $data)) {
            $success = "Pricing plan added successfully";
        } else {
            $error = "Failed to add pricing plan";
        }
    } else {
        $error = "Please fill all required fields";
    }
}

// Handle update pricing plan
if (isset($_POST['update_plan'])) {
    $id = filter_var($_POST['plan_id'], FILTER_VALIDATE_INT);
    $planName = trim($_POST['plan_name']);
    $coachingType = $_POST['coaching_type'];
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $duration = trim($_POST['duration']);
    $bookingUrl = trim($_POST['booking_url']);
    $buttonText = trim($_POST['button_text']);
    $description = trim($_POST['description']);
    $features = trim($_POST['features']);
    $displayOrder = filter_var($_POST['display_order'], FILTER_VALIDATE_INT);
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    
    if ($id && !empty($planName) && $price !== false) {
        $data = [
            'plan_name' => $planName,
            'coaching_type' => $coachingType,
            'price' => $price,
            'duration' => $duration,
            'booking_url' => $bookingUrl,
            'button_text' => $buttonText,
            'description' => $description,
            'features' => $features,
            'display_order' => $displayOrder,
            'is_active' => $isActive
        ];
        
        if (update($pdo, 'pricing_plans', $data, $id)) {
            $success = "Pricing plan updated successfully";
        } else {
            $error = "Failed to update pricing plan";
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($id && delete($pdo, 'pricing_plans', $id)) {
        $success = "Pricing plan deleted successfully";
    } else {
        $error = "Failed to delete pricing plan";
    }
}

// Get pricing plan for editing
$editPlan = null;
if (isset($_GET['edit'])) {
    $editId = filter_var($_GET['edit'], FILTER_VALIDATE_INT);
    if ($editId) {
        $editPlan = readOne($pdo, 'pricing_plans', $editId);
    }
}

// Get all pricing plans
$allPlans = readAll($pdo, 'pricing_plans', 'display_order', 'ASC');
$faceToFacePlans = readWhere($pdo, 'pricing_plans', 'coaching_type', 'face_to_face', 'display_order', 'ASC');
$onlinePlans = readWhere($pdo, 'pricing_plans', 'coaching_type', 'online', 'display_order', 'ASC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pricing</title>
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
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group-full {
            grid-column: 1 / -1;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"], input[type="number"], select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
        }
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
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
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .pricing-section {
            margin-bottom: 30px;
        }
        .pricing-section h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .pricing-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
        }
        .pricing-card h3 {
            color: #667eea;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .pricing-card .price {
            font-size: 36px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
        }
        .pricing-card .duration {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .pricing-card .description {
            color: #555;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .pricing-card .features {
            color: #666;
            white-space: pre-line;
            line-height: 1.8;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .pricing-card .actions {
            display: flex;
            gap: 10px;
        }
        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            background: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Manage Pricing Plans</h1>
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
                <h3><?php echo count($allPlans); ?></h3>
                <p>Total Plans</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count($faceToFacePlans); ?></h3>
                <p>Face-to-Face Plans</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count($onlinePlans); ?></h3>
                <p>Online Plans</p>
            </div>
        </div>
        
        <div class="form-container">
            <h2><?php echo $editPlan ? 'Edit Pricing Plan' : 'Add New Pricing Plan'; ?></h2>
            <form method="POST">
                <?php if ($editPlan): ?>
                    <input type="hidden" name="plan_id" value="<?php echo $editPlan['id']; ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="plan_name">Plan Name *</label>
                        <input readonly type="text" id="plan_name" name="plan_name" 
                               value="<?php echo $editPlan ? htmlspecialchars($editPlan['plan_name']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="coaching_type">Coaching Type *</label>
                        <select id="coaching_type" name="coaching_type" required>
                            <option value="face_to_face" <?php echo ($editPlan && $editPlan['coaching_type'] == 'face_to_face') ? 'selected' : ''; ?>>Face-to-Face</option>
                            <option value="online" <?php echo ($editPlan && $editPlan['coaching_type'] == 'online') ? 'selected' : ''; ?>>Online</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price (DH) *</label>
                        <input type="number" id="price" name="price" step="0.01" 
                               value="<?php echo $editPlan ? $editPlan['price'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="duration">Duration</label>
                        <input type="text" id="duration" name="duration" 
                               placeholder="e.g., per month, per session" 
                               value="<?php echo $editPlan ? htmlspecialchars($editPlan['duration']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="booking_url">Booking URL</label>
                        <input type="text" id="booking_url" name="booking_url" 
                               value="<?php echo $editPlan ? htmlspecialchars($editPlan['booking_url'] ?? '') : ''; ?>" placeholder="e.g., contact.php">
                    </div>
                    
                    <div class="form-group">
                        <label for="button_text">Button Text</label>
                        <input type="text" id="button_text" name="button_text" 
                               value="<?php echo $editPlan ? htmlspecialchars($editPlan['button_text'] ?? '') : ''; ?>" placeholder="Default: Get Started">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="display_order">Display Order</label>
                        <input type="number" id="display_order" name="display_order" 
                               value="<?php echo $editPlan ? $editPlan['display_order'] : '0'; ?>">
                    </div>
                    
                    <?php if ($editPlan): ?>
                        <div class="form-group">
                            <label class="checkbox-group">
                                <input type="checkbox" name="is_active" 
                                       <?php echo $editPlan['is_active'] ? 'checked' : ''; ?>>
                                Active Plan
                            </label>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group form-group-full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo $editPlan ? htmlspecialchars($editPlan['description']) : ''; ?></textarea>
                </div>
                
                <div class="form-group form-group-full">
                    <label for="features">Features (one per line)</label>
                    <textarea id="features" name="features" rows="5"><?php echo $editPlan ? htmlspecialchars($editPlan['features']) : ''; ?></textarea>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="<?php echo $editPlan ? 'update_plan' : 'add_plan'; ?>" class="btn btn-primary">
                        <?php echo $editPlan ? 'Update Plan' : 'Add Plan'; ?>
                    </button>
                    <?php if ($editPlan): ?>
                        <a href="manage_pricing.php" class="btn btn-secondary">Cancel Edit</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <div class="pricing-section">
            <h2>Face-to-Face Coaching Plans</h2>
            <?php if (count($faceToFacePlans) > 0): ?>
                <div class="pricing-grid">
                    <?php foreach ($faceToFacePlans as $plan): ?>
                        <div class="pricing-card">
                            <span class="status-badge status-<?php echo $plan['is_active'] ? 'active' : 'inactive'; ?>">
                                <?php echo $plan['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                            <h3><?php echo htmlspecialchars($plan['plan_name']); ?></h3>
                            <div class="price"><?php echo number_format($plan['price'], 2); ?> DH</div>
                            <div class="duration"><?php echo htmlspecialchars($plan['duration']); ?></div>
                            <div class="description"><?php echo htmlspecialchars($plan['description']); ?></div>
                            <div class="features"><?php echo htmlspecialchars($plan['features']); ?></div>
                            <div class="actions">
                                <a href="?edit=<?php echo $plan['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="?delete=<?php echo $plan['id']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Delete this pricing plan?')">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-data">No face-to-face plans yet</div>
            <?php endif; ?>
        </div>
        
        <div class="pricing-section">
            <h2>Online Coaching Plans</h2>
            <?php if (count($onlinePlans) > 0): ?>
                <div class="pricing-grid">
                    <?php foreach ($onlinePlans as $plan): ?>
                        <div class="pricing-card">
                            <span class="status-badge status-<?php echo $plan['is_active'] ? 'active' : 'inactive'; ?>">
                                <?php echo $plan['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                            <h3><?php echo htmlspecialchars($plan['plan_name']); ?></h3>
                            <div class="price"><?php echo number_format($plan['price'], 2); ?> DH</div>
                            <div class="duration"><?php echo htmlspecialchars($plan['duration']); ?></div>
                            <div class="description"><?php echo htmlspecialchars($plan['description']); ?></div>
                            <div class="features"><?php echo htmlspecialchars($plan['features']); ?></div>
                            <div class="actions">
                                <a href="?edit=<?php echo $plan['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="?delete=<?php echo $plan['id']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Delete this pricing plan?')">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-data">No online plans yet</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
