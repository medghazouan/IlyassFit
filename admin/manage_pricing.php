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
            // Redirect to clear form and show success message
            header('Location: manage_pricing.php?success=added');
            exit;
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
            // Redirect to clear form and show success message
            header('Location: manage_pricing.php?success=updated');
            exit;
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
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/manage_pricing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <!-- Main Content Area -->
    <div class="main-content">
        <div class="top-bar">
            <h1>Manage Pricing Plans</h1>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
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
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="fas fa-tags"></i>

                    </div>
                    <div class="stat-info">
                        <h3><?php echo count($allPlans); ?></h3>
                        <p>Total Plans</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon face">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo count($faceToFacePlans); ?></h3>
                        <p>Face-to-Face Plans</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon online">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo count($onlinePlans); ?></h3>
                        <p>Online Plans</p>
                    </div>
                </div>
            </div>
            
            <!-- Form Container -->
            <div class="form-container">
                <h2>
                    <i class="fas <?php echo $editPlan ? 'fa-edit' : 'fa-plus-circle'; ?>"></i>
                    <?php echo $editPlan ? 'Edit Pricing Plan' : 'Add New Pricing Plan'; ?>
                </h2>
                <form method="POST">
                    <?php if ($editPlan): ?>
                        <input type="hidden" name="plan_id" value="<?php echo $editPlan['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="plan_name">Plan Name *</label>
                            <input type="text" id="plan_name" name="plan_name" 
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
                                   value="<?php echo $editPlan ? htmlspecialchars($editPlan['booking_url'] ?? '') : ''; ?>" 
                                   placeholder="e.g., contact.php">
                        </div>
                        
                        <div class="form-group">
                            <label for="button_text">Button Text</label>
                            <input type="text" id="button_text" name="button_text" 
                                   value="<?php echo $editPlan ? htmlspecialchars($editPlan['button_text'] ?? '') : ''; ?>" 
                                   placeholder="Default: Get Started">
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
                                <label class="checkbox-label">
                                    <input type="checkbox" name="is_active" 
                                           <?php echo $editPlan['is_active'] ? 'checked' : ''; ?>>
                                    <span>Active Plan</span>
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"><?php echo $editPlan ? htmlspecialchars($editPlan['description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="features">Features (one per line)</label>
                        <textarea id="features" name="features" rows="5"><?php echo $editPlan ? htmlspecialchars($editPlan['features']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="<?php echo $editPlan ? 'update_plan' : 'add_plan'; ?>" 
                                class="btn <?php echo $editPlan ? 'btn-success' : 'btn-primary'; ?>">
                            <i class="fas <?php echo $editPlan ? 'fa-save' : 'fa-plus'; ?>"></i>
                            <?php echo $editPlan ? 'Update Plan' : 'Add Plan'; ?>
                        </button>
                        <?php if ($editPlan): ?>
                            <a href="manage_pricing.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            
            <!-- Face-to-Face Plans -->
            <div class="pricing-section">
                <div class="section-header">
                    <h2><i class="fas fa-users"></i> Face-to-Face Coaching Plans</h2>
                </div>
                
                <?php if (count($faceToFacePlans) > 0): ?>
                    <div class="pricing-grid">
                        <?php foreach ($faceToFacePlans as $plan): ?>
                            <div class="pricing-card">
                                <span class="status-badge status-<?php echo $plan['is_active'] ? 'active' : 'inactive'; ?>">
                                    <i class="fas fa-circle"></i>
                                    <?php echo $plan['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                                <h3><?php echo htmlspecialchars($plan['plan_name']); ?></h3>
                                <div class="price">
                                    <span class="amount"><?php echo number_format($plan['price'], 2); ?></span>
                                    <span class="currency">DH</span>
                                </div>
                                <div class="duration"><?php echo htmlspecialchars($plan['duration']); ?></div>
                                <div class="description"><?php echo htmlspecialchars($plan['description']); ?></div>
                                <div class="features">
                                    <?php 
                                    $featuresList = explode("\n", $plan['features']);
                                    foreach ($featuresList as $feature): 
                                        if (trim($feature)):
                                    ?>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span><?php echo htmlspecialchars(trim($feature)); ?></span>
                                        </div>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </div>
                                <div class="card-actions">
                                    <a href="?edit=<?php echo $plan['id']; ?>" class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $plan['id']; ?>" 
                                       class="btn-action btn-delete" 
                                       title="Delete"
                                       onclick="return confirm('Delete this pricing plan?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-inbox"></i>
                        <p>No face-to-face plans yet. Add your first plan above.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Online Plans -->
            <div class="pricing-section">
                <div class="section-header">
                    <h2><i class="fas fa-laptop"></i> Online Coaching Plans</h2>
                </div>
                
                <?php if (count($onlinePlans) > 0): ?>
                    <div class="pricing-grid">
                        <?php foreach ($onlinePlans as $plan): ?>
                            <div class="pricing-card">
                                <span class="status-badge status-<?php echo $plan['is_active'] ? 'active' : 'inactive'; ?>">
                                    <i class="fas fa-circle"></i>
                                    <?php echo $plan['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                                <h3><?php echo htmlspecialchars($plan['plan_name']); ?></h3>
                                <div class="price">
                                    <span class="amount"><?php echo number_format($plan['price'], 2); ?></span>
                                    <span class="currency">DH</span>
                                </div>
                                <div class="duration"><?php echo htmlspecialchars($plan['duration']); ?></div>
                                <div class="description"><?php echo htmlspecialchars($plan['description']); ?></div>
                                <div class="features">
                                    <?php 
                                    $featuresList = explode("\n", $plan['features']);
                                    foreach ($featuresList as $feature): 
                                        if (trim($feature)):
                                    ?>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span><?php echo htmlspecialchars(trim($feature)); ?></span>
                                        </div>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </div>
                                <div class="card-actions">
                                    <a href="?edit=<?php echo $plan['id']; ?>" class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $plan['id']; ?>" 
                                       class="btn-action btn-delete" 
                                       title="Delete"
                                       onclick="return confirm('Delete this pricing plan?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-inbox"></i>
                        <p>No online plans yet. Add your first plan above.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
