<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <!-- Mobile Logo -->
        <a class="navbar-brand mobile-logo" href="index.php">
            <img src="assets/images/static/logo.png" alt="Ilyass Fit Logo" class="logo-mobile">
        </a>

        <div class="navbar-content">
            <!-- Left Navigation Links -->
            <ul class="navbar-nav navbar-left">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php#about' ? 'active' : ''; ?>" href="index.php#about">ABOUT</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php#gallery' ? 'active' : ''; ?>" href="index.php#gallery">GALLERY</a>
                </li>
                
                
            </ul>
            
            <!-- Center Logo -->
            <a class="navbar-brand-center" href="index.php">
                <img src="assets/images/static/logo.png" alt="Ilyass Fit Logo" class="logo-center">
            </a>
            
            <!-- Right Navigation Links -->
            <ul class="navbar-nav navbar-right">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'pricing.php' ? 'active' : ''; ?>" href="pricing.php">PRICING</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'transformations.php' ? 'active' : ''; ?>" href="transformations.php">TRANSFORMS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" href="contact.php">CONTACT</a>
                </li>
                
            </ul>
        </div>
        
        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMobile">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Mobile Menu -->
        <div class="collapse navbar-collapse" id="navbarMobile">
            <ul class="navbar-nav mobile-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'pricing.php' ? 'active' : ''; ?>" href="pricing.php">PRICING</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'transformations.php' ? 'active' : ''; ?>" href="transformations.php">TRANSFORMS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" href="contact.php">CONTACT</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Navbar Scroll Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
});
</script>