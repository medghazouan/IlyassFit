<?php
// Set page title
$pageTitle = "Home";

// Define Services for Slider
$services = [
    [
        'title' => 'CUSTOM NUTRITION PLAN',
        'desc' => 'Healthy, Nutritious, Non-Restrictive Meal Plans Tailored Around You - Your Job, Lifestyle, Goals & Dietary Requirements And Your Budget Also Designed To Enable You To Enjoy A Tasty Varied Diet Making It Sustainable In The Long Term'
    ],
    [
        'title' => '1-ON-1 TRAINING',
        'desc' => 'Personalized workout sessions designed to push your limits safely. Focus on form, technique, and progressive overload to build strength and muscle effectively while minimizing injury risk.'
    ],
    [
        'title' => 'ONLINE COACHING',
        'desc' => 'Expert guidance from anywhere in the world. Includes customized workout programs, weekly check-ins, form analysis, and 24/7 support to ensure you stay on track towards your fitness goals.'
    ]
];

// Include meta and header
include 'components/meta.php';
include 'components/header.php';
?>

<!-- Hero Section -->
<!-- Hero Section -->
<section class="hero">
    <div class="hero-video-container">
        <video class="hero-video" autoplay muted loop playsinline poster="assets/images/static/hero_contact2.png">
            <source src="assets/video/hero.webm" type="video/webm">
            <source src="assets/video/hero.mp4" type="video/mp4">
            <!-- Fallback text/image if video fails is handled by poster and CSS background -->
        </video>
    </div>
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">TRANSFORM YOUR BODY</h1>
            <p class="hero-subtitle">Professional Training | Nutrition Coaching | Results Guaranteed</p>
            <div class="hero-buttons">
                <a href="pricing.php" class="btn btn-primary btn-lg">Get Started</a>
                <a href="#about" class="btn btn-outline-light btn-lg">Learn More</a>
            </div>
        </div>
    </div>
</section>

<?php
// Fetch reviews for About section
require_once __DIR__ . '/../includes/config/db_config.php';
$aboutReviews = [];
try {
    $stmt = $pdo->query("SELECT * FROM reviews WHERE status = 'approved' ORDER BY id DESC LIMIT 5");
    $aboutReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Reviews fetch error: " . $e->getMessage());
}

// Fetch 3 latest transformations for snippet
$transformations = [];
try {
    $stmt = $pdo->query("SELECT * FROM reviews WHERE status = 'approved' ORDER BY id DESC LIMIT 3");
    $transformations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Transformations fetch error: " . $e->getMessage());
}
?>

<!-- About Me Section -->
<section class="about-me-section" id="about">
    <div class="about-background-image"></div>
    <div class="container h-100">
        <div class="about-me-container h-100">
            <!-- Content Side -->
            <div class="about-me-content slide-in-right">
                <div class="about-header">
                    <span class="about-subtitle">ILYASS PT</span>
                    <h1 class="about-me-title">Get to<br>know me.</h1>
                </div>

                <p class="about-me-text">
                    Since my early years, physical excellence has always been my passion.
                </p>

                <p class="about-me-text">
                    Over the years, I have studied and tried many strategies to achieve it, 
                    realizing that the journey itself is as valuable as the destination and that 
                    having a structurally sound plan in place is of paramount importance.
                </p>

                <p class="about-me-text">
                    Therefore, I am confident in stating that with my knowledge and first-hand experience, 
                    I will guide you to reach your goals faster and injury-free by creating bespoke programs 
                    tailored to your personal needs and current limitations.
                </p>

                <p class="about-me-text">
                    Let me help you unlock the fittest version of yourself.
                </p>

                <!-- Testimonial -->
                <?php if (!empty($aboutReviews)): ?>
                <div class="about-testimonial mt-4 slide-in-left">
                    <div class="testimonial-content">
                        <i class="fas fa-chevron-left testimonial-arrow" id="prevReview"></i>
                        <div class="testimonial-text-wrapper">
                            <?php foreach ($aboutReviews as $index => $review): ?>
                                <div class="review-item <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                                    <p class="testimonial-quote">"<?php echo htmlspecialchars($review['review_text']); ?>"</p>
                                    <p class="testimonial-author"><?php echo htmlspecialchars($review['client_name']); ?></p>
                                </div>
                            <?php endforeach; ?>
                            <div class="testimonial-dots">
                                <?php foreach ($aboutReviews as $index => $review): ?>
                                    <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>"></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right testimonial-arrow" id="nextReview"></i>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll Animations
    const observerOptions = {
        threshold: 0.2
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                // If it's the section, trigger the background animation
                if (entry.target.classList.contains('about-me-section')) {
                    document.querySelector('.about-background-image').classList.add('visible');
                }
            }
        });
    }, observerOptions);

    const animatedElements = document.querySelectorAll('.slide-in-right, .slide-in-left');
    animatedElements.forEach(el => observer.observe(el));
    
    // Observer for the section itself to trigger background animation
    const section = document.querySelector('.about-me-section');
    if(section) observer.observe(section);

    // Testimonial Slider
    const reviews = document.querySelectorAll('.review-item');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.getElementById('prevReview');
    const nextBtn = document.getElementById('nextReview');
    let currentIndex = 0;

    function showReview(index) {
        reviews.forEach(review => review.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        reviews[index].classList.add('active');
        dots[index].classList.add('active');
    }

    if (prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + reviews.length) % reviews.length;
            showReview(currentIndex);
        });

        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % reviews.length;
            showReview(currentIndex);
        });

        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                currentIndex = parseInt(dot.getAttribute('data-index'));
                showReview(currentIndex);
            });
        });
    }
});
</script>


</script>

<!-- Services Section Custom -->
<section class="services-custom-section" id="services">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <!-- Left Side: Text Slider -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="services-content-wrapper">
                    
                    
                    <h2 class="services-main-title slide-in-left">OUR SERVICES.</h2>
                    
                    <div class="services-slider slide-in-left" style="transition-delay: 0.6s;">
                        <?php foreach ($services as $index => $service): ?>
                        <div class="service-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                            <h3 class="service-item-title"><?php echo $service['title']; ?></h3>
                            <p class="service-item-desc">
                                <?php echo $service['desc']; ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    </div>

                   
                </div>
            </div>

            <!-- Right Side: Static Image -->
            <div class="col-lg-6">
                <div class="service-image-container slide-in-right" style="transition-delay: 0.3s;">
                    <img src="assets/images/static/pic1.jpeg" alt="Fitness Services" class="img-fluid service-static-img">
                     <!-- Floating icons or dots could go here if needed -->
                     <div class="service-dots-nav">
                        <?php foreach ($services as $index => $service): ?>
                            <span class="service-dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="goToService(<?php echo $index; ?>)"></span>
                        <?php endforeach; ?>
                     </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    let currentServiceIndex = 0;
    const serviceSlides = document.querySelectorAll('.service-slide');
    const serviceDots = document.querySelectorAll('.service-dot');
    const totalServices = serviceSlides.length;
    let serviceInterval;

    function showService(index) {
        // Reset all
        serviceSlides.forEach(slide => {
            slide.classList.remove('active');
            slide.style.opacity = '0';
            slide.style.transform = 'translateY(20px)';
        });
        serviceDots.forEach(dot => dot.classList.remove('active'));

        // Activate current
        serviceSlides[index].classList.add('active');
        serviceDots[index].classList.add('active');
        
        // Slight delay for animation effect
        setTimeout(() => {
            serviceSlides[index].style.opacity = '1';
            serviceSlides[index].style.transform = 'translateY(0)';
        }, 50);

        currentServiceIndex = index;
    }

    function nextService() {
        let nextIndex = (currentServiceIndex + 1) % totalServices;
        showService(nextIndex);
    }

    function goToService(index) {
        clearInterval(serviceInterval); // Pause auto-slide on interaction
        showService(index);
        startServiceSlider(); // Restart
    }

    function startServiceSlider() {
        serviceInterval = setInterval(nextService, 5000); // Change every 5 seconds
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        // Initial state for first slide
        if(serviceSlides.length > 0) {
             serviceSlides[0].style.opacity = '1';
             serviceSlides[0].style.transform = 'translateY(0)';
             startServiceSlider();
        }
    });
</script>

<!-- Transformations Snippet Section -->
<section class="transformations-section" style="padding-top: 50px; padding-bottom: 50px;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="transformations-title">LATEST TRANSFORMATIONS</h2>
        </div>
        
        <div class="row g-4 justify-content-center">
            <?php foreach ($transformations as $review): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="transformation-card h-100">
                        <!-- Images Container Only -->
                        <div class="images-container" style="border-bottom: none;">
                            <div class="image-wrapper">
                                <div class="image-label-top">Before</div>
                                <?php 
                                $beforePath = $review['client_photo_before'];
                                if (!str_contains($beforePath, '/')) {
                                    $beforePath = 'images/uploads/' . $beforePath;
                                }
                                ?>
                                <img src="<?php echo htmlspecialchars($beforePath); ?>" 
                                     alt="Before" 
                                     class="transformation-img" style="height: 300px;">
                            </div>
                            <div class="divider-line"></div>
                            <div class="image-wrapper">
                                <div class="image-label-top">After</div>
                                <?php 
                                $afterPath = $review['client_photo_after'];
                                if (!str_contains($afterPath, '/')) {
                                    $afterPath = 'images/uploads/' . $afterPath;
                                }
                                ?>
                                <img src="<?php echo htmlspecialchars($afterPath); ?>" 
                                     alt="After" 
                                     class="transformation-img" style="height: 300px;">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="transformations.php" class="btn btn-primary btn-lg" style="padding: 15px 40px; font-size: 1.1rem; border-radius: 50px;">See More</a>
        </div>
    </div>
</section>


<!-- Gallery section -->
<section class="gallery-section" id="gallery">
    <div class="container">
        <!-- Gallery Header -->
        <div class="gallery-header">
            <h2 class="gallery-title">OUR GALLERY</h2>
            <p class="gallery-subtitle">Witness the Transformations & Training Sessions</p>
        </div>

        <?php
        // Include database configuration
        require_once __DIR__ . '/../includes/config/db_config.php';

        try {
            // Fetch 13 images from gallery table
            $stmt = $pdo->query("SELECT id, image_path FROM gallery ORDER BY id ASC LIMIT 13");
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Distribute images specifically: Left (5), Mid (3), Right (5)
            $colLeft = array_slice($images, 0, 5);
            $colMid = array_slice($images, 5, 3);
            $colRight = array_slice($images, 8, 5);
            
        } catch (PDOException $e) {
            error_log("Gallery fetch error: " . $e->getMessage());
            $colLeft = [];
            $colMid = [];
            $colRight = [];
        }
        ?>

        <div class="row g-4 gallery-grid-custom">
            <!-- Left Column (Scrolling) -->
            <div class="col-lg-4 col-md-4 gallery-col-scroll">
                <div class="d-flex flex-column gap-4">
                    <?php foreach ($colLeft as $image): ?>
                        <div class="gallery-item">
                            <img class="img-fluid rounded gallery-img" 
                                 src="images/uploads/<?php echo htmlspecialchars($image['image_path']); ?>" 
                                 alt="Gym Gallery">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Middle Column (Sticky) -->
            <div class="col-lg-4 col-md-4 gallery-col-sticky-wrapper">
                <div class="d-flex flex-column gap-4 gallery-sticky-content">
                    <?php foreach ($colMid as $image): ?>
                        <div class="gallery-item">
                            <img class="img-fluid rounded gallery-img" 
                                 src="images/uploads/<?php echo htmlspecialchars($image['image_path']); ?>" 
                                 alt="Gym Gallery">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right Column (Scrolling) -->
            <div class="col-lg-4 col-md-4 gallery-col-scroll mt-lg-5 mt-md-5"> <!-- Added margin top for staggering effect -->
                <div class="d-flex flex-column gap-4">
                    <?php foreach ($colRight as $image): ?>
                        <div class="gallery-item">
                            <img class="img-fluid rounded gallery-img" 
                                 src="images/uploads/<?php echo htmlspecialchars($image['image_path']); ?>" 
                                 alt="Gym Gallery">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Instagram Button -->
        <div class="text-center mt-5">
            <a href="https://instagram.com/ilyassfit" target="_blank" class="btn btn-outline-light btn-lg btn-instagram">
                <i class="fab fa-instagram me-2"></i> Follow Me on Instagram
            </a>
        </div>
    </div>
</section>




<?php
// Include footer
include 'components/footer.php';
?>