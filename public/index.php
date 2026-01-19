<?php
// Set page title and description for SEO
$pageTitle = "Personal Training & Online Coaching";
$pageDescription = "Transform your body with Ilyass Fit. Professional personal training, custom nutrition plans, and online coaching worldwide. Get started today!";

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
<?php 
$heroTitle = "TRANSFORM YOUR BODY";
$heroSubtitle = "Professional Training | Nutrition Coaching | Results Guaranteed";
$heroBackground = "hero-bg-video";
ob_start(); 
?>
<a href="pricing.php" class="btn btn-primary">Get Started</a>
<a href="#about" class="btn btn-outline-light">Learn More</a>
<?php 
$heroButtons = ob_get_clean();
include 'components/hero.php'; 
?>

<?php
// Fetch reviews for About section
require_once __DIR__ . '/../includes/config/db_config.php';
$aboutReviews = [];
try {
    $stmt = $pdo->query("SELECT * FROM reviews ORDER BY id DESC LIMIT 5");
    $aboutReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Reviews fetch error: " . $e->getMessage());
}

// Fetch 3 latest transformations for snippet
$transformations = [];
try {
    $stmt = $pdo->query("SELECT * FROM reviews ORDER BY id DESC LIMIT 3");
    $transformations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Transformations fetch error: " . $e->getMessage());
}
?>

<!-- About Me Section -->
<section class="about-me-section" id="about">
    <div class="about-background-image"></div>
    <div class="container h-100">
        <div class="about-me-container h-100 ">
            <!-- Content Side -->
            <div class="about-me-content slide-in-right">
                <div class="about-header">
                    <span class="about-subtitle">ILYASS FIT</span>
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
                                    <div class="review-item <?php echo $index === 0 ? 'active' : ''; ?>"
                                        data-index="<?php echo $index; ?>">
                                        <p class="testimonial-quote">"<?php echo htmlspecialchars($review['review_text']); ?>"
                                        </p>
                                        <p class="testimonial-author"><?php echo htmlspecialchars($review['client_name']); ?>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                                <div class="testimonial-dots">
                                    <?php foreach ($aboutReviews as $index => $review): ?>
                                        <span class="dot <?php echo $index === 0 ? 'active' : ''; ?>"
                                            data-index="<?php echo $index; ?>"></span>
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



<!-- Services Section Custom -->
<section class="services-custom-section" id="services">
    <div class="container h-100">
        <div class="row h-100 align-items-center ">
            <!-- Left Side: Text Slider (Order 2 on mobile, Order 1 on Desktop) -->
            <div class="col-lg-6 mb-3 mb-lg-0 order-2 order-lg-1">
                <div class="services-content-wrapper">
                    <h2 class="services-main-title slide-in-left">OUR SERVICES.</h2>

                    <div class="services-slider slide-in-left" style="transition-delay: 0.6s;">
                        <?php foreach ($services as $index => $service): ?>
                            <div class="service-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                                data-index="<?php echo $index; ?>">
                                <h3 class="service-item-title"><?php echo $service['title']; ?></h3>
                                <p class="service-item-desc">
                                    <?php echo $service['desc']; ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Dots Navigation (Moved here to stay with content) -->
                         <div class="service-dots-nav">
                            <?php foreach ($services as $index => $service): ?>
                                <span class="service-dot <?php echo $index === 0 ? 'active' : ''; ?>" 
                                      onclick="goToService(<?php echo $index; ?>)"></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Static Image (Order 1 on mobile, Order 2 on Desktop) -->
            <div class="col-lg-6 order-1 order-lg-2">
                <div class="service-image-container slide-in-right" style="transition-delay: 0.3s;">
                    <img src="assets/images/static/service.webp" alt="Fitness Services"
                        class="img-fluid service-static-img">
                     <!-- Decor elements if needed -->
                    <div class="service-decor top-left">
                        <i class="fas fa-plus"></i>
                        <i class="fas fa-plus"></i>
                        <i class="fas fa-plus"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>



<!-- Transformations Snippet Section -->
<section class="transformations-section">
    <div class="container">
        <div class="gallery-header">
            <h2 class="gallery-title">LATEST TRANSFORMATIONS</h2>
            <p class="section-subtitle transformations-subtitle">Witness the incredible journeys of our clients who have completely transformed their physiques and lifestyles through dedicated training.</p>
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
                                <?php 
                                $extBefore = strtolower(pathinfo($beforePath, PATHINFO_EXTENSION));
                                if ($extBefore === 'webm'): 
                                ?>
                                    <video src="<?php echo htmlspecialchars($beforePath); ?>" 
                                           class="transformation-img"
                                           autoplay loop muted playsinline></video>
                                <?php else: ?>
                                    <img src="<?php echo htmlspecialchars($beforePath); ?>" alt="Before"
                                        class="transformation-img">
                                <?php endif; ?>
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
                                <?php 
                                $extAfter = strtolower(pathinfo($afterPath, PATHINFO_EXTENSION));
                                if ($extAfter === 'webm'): 
                                ?>
                                    <video src="<?php echo htmlspecialchars($afterPath); ?>" 
                                           class="transformation-img"
                                           autoplay loop muted playsinline></video>
                                <?php else: ?>
                                    <img src="<?php echo htmlspecialchars($afterPath); ?>" alt="After"
                                        class="transformation-img">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="transformations.php" id="seemorebtn" class="btn btn-outline-light">See More</a>
        </div>
    </div>
</section>

<!-- Expandable Gallery Section -->
<section class="gallery-section" id="gallery">
    <div class="container">
        <!-- Gallery Header -->
        <div class="gallery-header">
            <h2 class="gallery-title">OUR GALLERY</h2>
            <p class="section-subtitle">Explore our gallery to see the dedication, hard work, and results achieved by our community. From intense training sessions to inspiring transformations, get a glimpse of what's possible.</p>
        </div>

        <?php
        // Fetch 12 images from gallery table (3 groups of 4)
        try {
            $stmt = $pdo->query("SELECT id, image_path FROM gallery ORDER BY RAND() LIMIT 12");
            $allImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Create paths for images
            foreach ($allImages as &$img) {
                $img['full_path'] = 'images/uploads/' . htmlspecialchars($img['image_path']);
            }
        } catch (PDOException $e) {
            error_log("Gallery fetch error: " . $e->getMessage());
            $allImages = [];
        }
        ?>

        <!-- Expandable Gallery Container -->
        <div class="expandable-gallery-wrapper">
            <?php if (!empty($allImages)): ?>
                <!-- Group 1 -->
                <div class="gallery-group">
                    <div class="expandable-gallery" data-group="1">
                        <?php foreach (array_slice($allImages, 0, 4) as $index => $image): ?>
                            <div class="gallery-item-expandable" data-index="<?php echo $index; ?>">
                                <?php 
                                $ext = strtolower(pathinfo($image['image_path'], PATHINFO_EXTENSION));
                                if ($ext === 'webm'): 
                                ?>
                                    <video src="<?php echo $image['full_path']; ?>" 
                                           class="gallery-img-expandable"
                                           autoplay loop muted playsinline
                                           style="object-fit: cover; width: 100%; height: 100%;">
                                    </video>
                                <?php else: ?>
                                    <img src="<?php echo $image['full_path']; ?>" 
                                         alt="Gallery Image <?php echo $index + 1; ?>" 
                                         class="gallery-img-expandable">
                                <?php endif; ?>
                                <div class="gallery-overlay"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Group 2 -->
                <div class="gallery-group">
                    <div class="expandable-gallery" data-group="2">
                        <?php foreach (array_slice($allImages, 4, 4) as $index => $image): ?>
                            <div class="gallery-item-expandable" data-index="<?php echo $index; ?>">
                                <?php 
                                $ext = strtolower(pathinfo($image['image_path'], PATHINFO_EXTENSION));
                                if ($ext === 'webm'): 
                                ?>
                                    <video src="<?php echo $image['full_path']; ?>" 
                                           class="gallery-img-expandable"
                                           autoplay loop muted playsinline
                                           style="object-fit: cover; width: 100%; height: 100%;">
                                    </video>
                                <?php else: ?>
                                    <img src="<?php echo $image['full_path']; ?>" 
                                         alt="Gallery Image <?php echo $index + 5; ?>" 
                                         class="gallery-img-expandable">
                                <?php endif; ?>
                                <div class="gallery-overlay"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Group 3 -->
                <div class="gallery-group">
                    <div class="expandable-gallery" data-group="3">
                        <?php foreach (array_slice($allImages, 8, 4) as $index => $image): ?>
                            <div class="gallery-item-expandable" data-index="<?php echo $index; ?>">
                                <?php 
                                $ext = strtolower(pathinfo($image['image_path'], PATHINFO_EXTENSION));
                                if ($ext === 'webm'): 
                                ?>
                                    <video src="<?php echo $image['full_path']; ?>" 
                                           class="gallery-img-expandable"
                                           autoplay loop muted playsinline
                                           style="object-fit: cover; width: 100%; height: 100%;">
                                    </video>
                                <?php else: ?>
                                    <img src="<?php echo $image['full_path']; ?>" 
                                         alt="Gallery Image <?php echo $index + 9; ?>" 
                                         class="gallery-img-expandable">
                                <?php endif; ?>
                                <div class="gallery-overlay"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Gallery images will be displayed here.</div>
            <?php endif; ?>
        </div>

        <!-- Gallery Navigation Buttons -->
        <div class="text-center mt-5">
            <div class="d-flex flex-wrap justify-content-center gap-3 gallery-buttons">
                <a href="https://instagram.com" target="_blank"
                    class="btn btn-outline-light btn-instagram-custom">
                    <i class="fab fa-instagram me-2"></i> See More
                </a>
                <button class="btn btn-watch-now" id="openVideoModal">
                    <i class="fas fa-play-circle me-2"></i> Watch Now
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Image Expand Modal -->
<div class="gallery-modal" id="galleryModal">
    <div class="gallery-modal-overlay" id="galleryModalOverlay"></div>
    <div class="gallery-modal-content">
        <!-- Close Button -->
        <button class="gallery-modal-close" id="closeGalleryModal">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Previous Button -->
        <button class="gallery-nav-btn gallery-nav-prev" id="galleryPrevBtn">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Image Container -->
        <div class="gallery-modal-image-container">
            <img id="galleryModalImg" src="" alt="Expanded Image" class="gallery-modal-img">
        </div>

        <!-- Next Button -->
        <button class="gallery-nav-btn gallery-nav-next" id="galleryNextBtn">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <!-- Image Counter -->
        <div class="gallery-modal-counter">
            <span id="galleryCurrentIndex">1</span> / <span id="galleryTotalImages">12</span>
        </div>
    </div>
</div>

<!-- Video Modal -->
<div class="video-modal" id="videoModal">
    <div class="video-modal-overlay" id="videoModalOverlay"></div>
    <div class="video-modal-content">
        <button class="video-modal-close" id="closeVideoModal">
            <i class="fas fa-times"></i>
        </button>
        <div class="video-wrapper">
            <video id="modalVideo" controls>
                <source src="assets/video/video.webp" type="video/webm">
                <source src="assets/video/video.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
</div>

<!-- JavaScript externalized to main.js for better performance and caching -->
<script src="assets/js/main.js"></script>


<?php
// Include footer
include 'components/footer.php';
?>