<?php
// Set page title
$pageTitle = "Home";

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
            // Fetch 12 images from gallery table
            $stmt = $pdo->query("SELECT id, image_path FROM gallery ORDER BY id ASC LIMIT 12");
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Distribute images evenly across 4 columns
            $columns = [[], [], [], []];
            foreach ($images as $index => $image) {
                $columnIndex = $index % 4;
                $columns[$columnIndex][] = $image;
            }
            
        } catch (PDOException $e) {
            error_log("Gallery fetch error: " . $e->getMessage());
            $columns = [[], [], [], []];
        }
        ?>

        <div class="row g-4">
            <?php foreach ($columns as $columnImages): ?>
                <div class="col-6 col-md-3">
                    <div class="d-flex flex-column gap-4">
                        <?php foreach ($columnImages as $image): ?>
                            <div>
                                <img class="img-fluid rounded" 
                                     src="images/uploads/<?php echo htmlspecialchars($image['image_path']); ?>" 
                                     alt="">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>




<?php
// Include footer
include 'components/footer.php';
?>