<!-- Sidebar Navigation Component -->
<div class="sidebar">
    <div class="logo-container">
        <picture>
            <source media="(max-width: 768px)" srcset="logo.png">
            <img src="adminlogo.png" alt="Logo" class="logo">
        </picture>
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
        <script>
            // Per-tab session binding: generate a tab id and notify server.
            (function(){
                try {
                    const storageKey = 'admin_tab_id';
                    let tabId = sessionStorage.getItem(storageKey);
                    if (!tabId) {
                        // generate random id
                        tabId = 'tab_' + Math.random().toString(36).slice(2) + Date.now().toString(36);
                        sessionStorage.setItem(storageKey, tabId);
                    }

                    fetch('validate_tab.php', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({tab_id: tabId})
                    }).then(resp => {
                        if (!resp.ok) {
                            // session invalid -> redirect to login
                            window.location.href = 'login.php';
                        }
                    }).catch(err => {
                        console.warn('Tab validation failed', err);
                    });
                } catch (e) {
                    console.warn('Tab binding error', e);
                }
            })();
        </script>
</div>

