<!-- Sidebar Navigation Component -->
<div class="sidebar">
    <div class="logo-container">
        <img src="adminlogo.png" alt="Logo" class="logo">
    </div>
    
    <nav class="nav-menu">
        <a href="dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="manage_reviews.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage_reviews.php' ? 'active' : ''; ?>">
            <i class="fas fa-star"></i>
            <span>Manage Reviews</span>
        </a>
        <a href="manage_gallery.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage_gallery.php' ? 'active' : ''; ?>">
            <i class="fas fa-images"></i>
            <span>Manage Gallery</span>
        </a>
        <a href="manage_messages.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage_messages.php' ? 'active' : ''; ?>">
            <i class="fas fa-envelope"></i>
            <span>View Messages</span>
        </a>
        <a href="manage_pricing.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage_pricing.php' ? 'active' : ''; ?>">
            <i class="fas fa-dollar-sign"></i>
            <span>Manage Pricing</span>
        </a>
    </nav>
    
    <div class="sidebar-footer">
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</div>
