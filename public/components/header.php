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
                    <a class="nav-link" href="index.php#about">ABOUT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#gallery">GALLERY</a>
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
    // Only run the index/anchor scroll/hash active-link logic when we're
    // actually on the homepage (or when the target sections exist). This
    // prevents overriding server-side `active` classes on other pages.
    const isIndexLike = location.pathname.endsWith('index.php') || location.pathname === '/' || document.getElementById('about') || document.getElementById('gallery');
    if (!isIndexLike) return;

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
    // Use a more forgiving rootMargin so sections become active when they
    // reach near the middle of the viewport, improving reliability for large
    // sections like the gallery.
    const observer = new IntersectionObserver((entries) => {
        // Track which observed sections are currently intersecting. We
        // maintain a Set so we can know when none are visible (show HOME).
        entries.forEach(entry => {
            const id = entry.target.id;
            if (!id) return;
            if (!window._visibleSections) window._visibleSections = new Set();
            if (entry.isIntersecting && entry.intersectionRatio > 0.15) {
                window._visibleSections.add(id);
            } else {
                window._visibleSections.delete(id);
            }
        });

        // Decide which nav link should be active.
        // Priority: observed ids in `idsToObserve` order, otherwise HOME.
        let activated = false;
        for (const key of idsToObserve) {
            if (window._visibleSections && window._visibleSections.has(key)) {
                document.querySelectorAll('.navbar-nav .nav-link').forEach(l => l.classList.remove('active'));
                document.querySelectorAll('.navbar-nav .nav-link[href*="#' + key + '"]').forEach(l => l.classList.add('active'));
                activated = true;
                break;
            }
        }

        if (!activated) {
            // No observed section visible -> mark HOME active (index.php links)
            document.querySelectorAll('.navbar-nav .nav-link').forEach(l => l.classList.remove('active'));
            // match links that point to index.php or to '/'
            const homeLink = document.querySelector('.navbar-nav .nav-link[href$="index.php"]') || document.querySelector('.navbar-nav .nav-link[href="/" ]');
            if (homeLink) homeLink.classList.add('active');
        }
    }, { root: null, rootMargin: '-30% 0px -30% 0px', threshold: [0.15] });

    idsToObserve.forEach(id => {
        const el = document.getElementById(id);
        if (el) observer.observe(el);
    });

    // Fallback: robust scroll-based visibility check using bounding rects.
    // Some large sections (like a gallery) can be missed by IntersectionObserver
    // depending on viewport and rootMargin; this checks visible area percentage
    // and picks the section with the largest visible portion.
    function getMostVisibleSection() {
        let best = { id: null, ratio: 0 };
        idsToObserve.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            const rect = el.getBoundingClientRect();
            const elHeight = rect.height || 1;
            const visible = Math.max(0, Math.min(rect.bottom, window.innerHeight) - Math.max(rect.top, 0));
            const ratio = visible / elHeight;
            if (ratio > best.ratio) best = { id, ratio };
        });
        return best;
    }

    let scheduled = false;
    function onScrollThrottled() {
        if (scheduled) return;
        scheduled = true;
        requestAnimationFrame(() => {
            scheduled = false;
            const best = getMostVisibleSection();
            if (best.id && best.ratio > 0.12) {
                document.querySelectorAll('.navbar-nav .nav-link').forEach(l => l.classList.remove('active'));
                document.querySelectorAll('.navbar-nav .nav-link[href*="#' + best.id + '"]').forEach(l => l.classList.add('active'));
            } else {
                // default to HOME when none are sufficiently visible
                document.querySelectorAll('.navbar-nav .nav-link').forEach(l => l.classList.remove('active'));
                const homeLink = document.querySelector('.navbar-nav .nav-link[href$="index.php"]') || document.querySelector('.navbar-nav .nav-link[href="/" ]');
                if (homeLink) homeLink.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', onScrollThrottled, { passive: true });
    window.addEventListener('resize', onScrollThrottled);

    // Run once on load to set correct state
    onScrollThrottled();
});
</script>