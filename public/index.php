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
<?php 
$heroTitle = "TRANSFORM YOUR BODY";
$heroSubtitle = "Professional Training | Nutrition Coaching | Results Guaranteed";
$heroBackground = "hero-bg-video";
ob_start(); 
?>
<a href="pricing.php" class="btn btn-primary btn-lg">Get Started</a>
<a href="#about" class="btn btn-outline-light btn-lg">Learn More</a>
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

<!-- Scripts moved to main.js for better performance -->

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
                            <div class="service-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                                data-index="<?php echo $index; ?>">
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
                    <img src="assets/images/static/pic1.jpeg" alt="Fitness Services"
                        class="img-fluid service-static-img">

                    <div class="service-dots-nav">
                        <?php foreach ($services as $index => $service): ?>
                            <span class="service-dot <?php echo $index === 0 ? 'active' : ''; ?>"
                                onclick="goToService(<?php echo $index; ?>)"></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Services slider script moved to main.js -->

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
                                <img src="<?php echo htmlspecialchars($beforePath); ?>" alt="Before"
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
                                <img src="<?php echo htmlspecialchars($afterPath); ?>" alt="After"
                                    class="transformation-img" style="height: 300px;">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="transformations.php" class="btn btn-primary btn-lg"
                style="padding: 15px 40px; font-size: 1.1rem; border-radius: 50px;">See More</a>
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
        // Database configuration already loaded at line 49
        
        try {
            // Fetch 13 images from gallery table
            $stmt = $pdo->query("SELECT id, image_path FROM gallery ORDER BY id ASC LIMIT 13");
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Distribute 13 images
            $leftTop = array_slice($images, 0, 2);      // Images 0-1 (2 images)
            $colMid = array_slice($images, 2, 3);       // Images 2-4 (3 images)
            $rightTop = array_slice($images, 5, 2);     // Images 5-6 (2 images)
            $rightBottom = array_slice($images, 7, 3);  // Images 7-9 (3 images)
            $leftBottom = array_slice($images, 10, 3);  // Images 10-12 (3 images)
        
        } catch (PDOException $e) {
            error_log("Gallery fetch error: " . $e->getMessage());
            $leftTop = [];
            $leftBottom = [];
            $colMid = [];
            $rightTop = [];
            $rightBottom = [];
        }
        ?>

        <div class="row g-4 gallery-grid-custom">
            <!-- Left Column (Scrolling) -->
            <div class="col-3 gallery-col-scroll">
                <div class="d-flex flex-column gap-3">
                    <?php
                    $leftImages = array_merge($leftTop, $leftBottom);
                    foreach ($leftImages as $image):
                        ?>
                        <div class="gallery-item">
                            <img class="img-fluid gallery-img"
                                src="images/uploads/<?php echo htmlspecialchars($image['image_path']); ?>" alt="Gym Gallery"
                                loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Middle Column (Sticky) -->
            <div class="col-6 gallery-col-sticky-wrapper">
                <div class="d-flex flex-column gap-4 gallery-sticky-content">
                    <?php foreach ($colMid as $image): ?>
                        <div class="gallery-item">
                            <img class="img-fluid gallery-img"
                                src="images/uploads/<?php echo htmlspecialchars($image['image_path']); ?>" alt="Gym Gallery"
                                loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right Column (Scrolling) -->
            <div class="col-3 gallery-col-scroll">
                <div class="d-flex flex-column gap-3">
                    <?php
                    $rightImages = array_merge($rightTop, $rightBottom);
                    foreach ($rightImages as $image):
                        ?>
                        <div class="gallery-item">
                            <img class="img-fluid gallery-img"
                                src="images/uploads/<?php echo htmlspecialchars($image['image_path']); ?>" alt="Gym Gallery"
                                loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Instagram Button & Watch Now Button -->
        <!-- Instagram Button & Watch Now Button -->
        <div class="text-center mt-5">
            <div class="d-flex flex-wrap justify-content-center gap-3 gallery-buttons">
                <a href="https://instagram.com" target="_blank"
                    class="btn btn-outline-light btn-lg btn-instagram-custom">
                    <i class="fab fa-instagram me-2"></i> See More
                </a>
                <button class="btn btn-lg btn-watch-now" id="openVideoModal">
                    <i class="fas fa-play-circle me-2"></i> Watch Now
                </button>
            </div>
        </div>

    </div>
</section>

<!-- Video Modal -->
<div class="video-modal" id="videoModal">
    <div class="video-modal-overlay" id="videoModalOverlay"></div>
    <div class="video-modal-content">
        <button class="video-modal-close" id="closeVideoModal">
            <i class="fas fa-times"></i>
        </button>
        <div class="video-wrapper">
            <video id="modalVideo" controls>
                <source src="assets/video/hero.webm" type="video/webm">
                <source src="assets/video/hero.mp4" type="video/mp4">
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