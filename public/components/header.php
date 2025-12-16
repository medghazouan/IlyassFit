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
                    <a class="nav-link" href="index.php#about">ABOUT</a>
                </li>
               
                <li class="nav-item">
                    <a class="nav-link" href="index.php#gallery">GALLERY</a>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    function setActiveByHash(hash) {
        if (!hash) return;
        document.querySelectorAll('.navbar-nav .nav-link').forEach(l => l.classList.remove('active'));
        document.querySelectorAll('.navbar-nav .nav-link[href*="' + hash + '"]').forEach(l => l.classList.add('active'));
    }

    // On load, if there's a hash (e.g. index.php#about), activate corresponding links
    if (location.hash) {
        setActiveByHash(location.hash);
    }

    // Update on hash change (back/forward or anchor links)
    window.addEventListener('hashchange', function() {
        setActiveByHash(location.hash);
    });

    // IntersectionObserver to update active state while scrolling on the index page
    const idsToObserve = ['about', 'gallery'];
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.id;
                if (!id) return;
                document.querySelectorAll('.navbar-nav .nav-link').forEach(l => l.classList.remove('active'));
                document.querySelectorAll('.navbar-nav .nav-link[href*="#' + id + '"]').forEach(l => l.classList.add('active'));
            }
        });
    }, { threshold: 0.5 });

    idsToObserve.forEach(id => {
        const el = document.getElementById(id);
        if (el) observer.observe(el);
    });
});
</script>