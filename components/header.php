<?php
// Navigation configuration - Single source of truth for all nav menus
$navLinks = [
    ['href' => 'index.php', 'label' => 'HOME', 'page' => 'index.php'],
    ['href' => 'index.php#about', 'label' => 'ABOUT', 'page' => 'index.php'],
    ['href' => 'index.php#gallery', 'label' => 'GALLERY', 'page' => 'index.php'],
    ['href' => 'pricing.php', 'label' => 'PRICING', 'page' => 'pricing.php'],
    ['href' => 'transformations.php', 'label' => 'TRANSFORMS', 'page' => 'transformations.php'],
    ['href' => 'contact.php', 'label' => 'CONTACT', 'page' => 'contact.php']
];
$currentPage = basename($_SERVER['PHP_SELF']);
?>
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
                <?php foreach (array_slice($navLinks, 0, 3) as $link): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage == $link['page'] ? 'active' : ''; ?>" 
                           href="<?php echo $link['href']; ?>"><?php echo $link['label']; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
           
            <!-- Center Logo -->
            <a class="navbar-brand-center" href="index.php">
                <img src="assets/images/static/logo.png" alt="Ilyass Fit Logo" class="logo-center">
            </a>
           
            <!-- Right Navigation Links -->
            <ul class="navbar-nav navbar-right">
                <?php foreach (array_slice($navLinks, 3, 3) as $link): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage == $link['page'] ? 'active' : ''; ?>" 
                           href="<?php echo $link['href']; ?>"><?php echo $link['label']; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
       
        <!-- Mobile Toggle (Hamburger) -->
        <button class="navbar-toggler" type="button" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
       
        <!-- Mobile Menu - Slides in from right -->
        <div class="collapse navbar-collapse" id="navbarMobile">
            <ul class="navbar-nav mobile-nav">
                <?php foreach ($navLinks as $link): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage == $link['page'] ? 'active' : ''; ?>" 
                           href="<?php echo $link['href']; ?>"><?php echo $link['label']; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="mobile-menu-footer">
                <img src="assets/images/static/logo.png" alt="Ilyass Fit Logo" class="logo-mobile-footer">
            </div>
        </div>
    </div>
</nav>

<!-- Navbar Scroll & Sticky Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    const gallerySection = document.getElementById('gallery');
    
    window.addEventListener('scroll', function() {
        const scrollPosition = window.scrollY;
        
        // Add scrolled class for background change
        if (scrollPosition > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        // Make navbar sticky only after reaching gallery section
        if (gallerySection) {
            const galleryOffset = gallerySection.offsetTop;
            
            if (scrollPosition >= galleryOffset) {
                navbar.classList.add('sticky');
            } else {
                navbar.classList.remove('sticky');
            }
        } else {
            // If no gallery section (like on other pages), navbar is always sticky
            navbar.classList.add('sticky');
        }
    });
    
    // Initial check on page load
    const initialScroll = window.scrollY;
    if (initialScroll > 50) {
        navbar.classList.add('scrolled');
    }
    
    if (!document.getElementById('gallery')) {
        navbar.classList.add('sticky');
    }
});
</script>

<!-- Mobile Menu Toggle Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.getElementById('navbarMobile');
    const body = document.body;
    
    if (navbarToggler && navbarCollapse) {
        // Toggle menu on hamburger/X click
        navbarToggler.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isOpen = navbarCollapse.classList.contains('show');
            
            if (isOpen) {
                // Close menu
                navbarCollapse.classList.remove('show');
                body.classList.remove('menu-open');
                navbarToggler.setAttribute('aria-expanded', 'false');
            } else {
                // Open menu
                navbarCollapse.classList.add('show');
                body.classList.add('menu-open');
                navbarToggler.setAttribute('aria-expanded', 'true');
            }
        });
        
        // Close menu when clicking on backdrop
        // Since we removed the backdrop overlay CSS, this might not work as intended for "clicking outside".
        // However, if we want "click outside" to close, we need to detect clicks on document that are NOT inside the menu.
        document.addEventListener('click', function(e) {
            if (body.classList.contains('menu-open')) {
                // Check if click is outside menu AND not on the toggler button
                if (!navbarCollapse.contains(e.target) && !navbarToggler.contains(e.target)) {
                    navbarCollapse.classList.remove('show');
                    body.classList.remove('menu-open');
                    navbarToggler.setAttribute('aria-expanded', 'false');
                }
            }
        });
        
        // Close mobile menu when clicking on a nav link
        const mobileNavLinks = navbarCollapse.querySelectorAll('.nav-link');
        mobileNavLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                // Do NOT prevent default here, let the link navigate!
                // Just close the menu visually
                navbarCollapse.classList.remove('show');
                body.classList.remove('menu-open');
                navbarToggler.setAttribute('aria-expanded', 'false');
            });
        });
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && body.classList.contains('menu-open')) {
                navbarCollapse.classList.remove('show');
                body.classList.remove('menu-open');
                navbarToggler.setAttribute('aria-expanded', 'false');
            }
        });
    }
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
    const observer = new IntersectionObserver((entries) => {
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
            document.querySelectorAll('.navbar-nav .nav-link').forEach(l => l.classList.remove('active'));
            const homeLink = document.querySelector('.navbar-nav .nav-link[href$="index.php"]') || document.querySelector('.navbar-nav .nav-link[href="/" ]');
            if (homeLink) homeLink.classList.add('active');
        }
    }, { root: null, rootMargin: '-30% 0px -30% 0px', threshold: [0.15] });

    idsToObserve.forEach(id => {
        const el = document.getElementById(id);
        if (el) observer.observe(el);
    });

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
                document.querySelectorAll('.navbar-nav .nav-link').forEach(l => l.classList.remove('active'));
                const homeLink = document.querySelector('.navbar-nav .nav-link[href$="index.php"]') || document.querySelector('.navbar-nav .nav-link[href="/" ]');
                if (homeLink) homeLink.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', onScrollThrottled, { passive: true });
    window.addEventListener('resize', onScrollThrottled);

    onScrollThrottled();
});
</script>