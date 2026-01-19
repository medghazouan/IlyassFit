<?php
require_once '../includes/config/db_config.php';

// Fetch approved reviews from database
$stmt = $pdo->prepare("SELECT * FROM reviews ORDER BY id DESC");
$stmt->execute();
$reviews = $stmt->fetchAll();

$pageTitle = "Client Transformations";
$pageDescription = "See real before and after results from Ilyass Fit clients. Inspiring body transformations through dedicated training and nutrition coaching.";
include 'components/meta.php';
include 'components/header.php';
?>


<!-- Hero Section -->
<?php 
$heroTitle = "CLIENT TRANSFORMATIONS";
$heroSubtitle = "Real Results, Real People";
$heroBackground = "hero-bg-transformations";
ob_start(); 
?>
<a href="pricing.php" class="btn btn-primary btn-lg">Get Started</a>
<a href="contact.php" class="btn btn-outline-light btn-lg">Learn More</a>
<?php 
$heroButtons = ob_get_clean();
include 'components/hero.php'; 
?>

<!-- Transformations Section -->
<section class="transformations-section">
    <div class="container py-5">
        <!-- Section Title -->
        <div class="text-center mb-5">
            <h2 class="transformations-title">SUCCESS STORIES</h2>
            <p class="transformations-subtitle">See the amazing transformations of our clients</p>
        </div>
        
        <div class="row g-4">  <!-- REMOVE justify-content-center -->
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $index => $review): ?>
                    <div class="col-md-6 col-lg-4 <?php echo ($index < 3) ? 'first-row' : ''; ?>">
                        <div class="transformation-card">
                            <!-- Images Container -->
                            <div class="images-container">
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
                                        <img src="<?php echo htmlspecialchars($beforePath); ?>" 
                                             alt="Before" 
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
                                        <img src="<?php echo htmlspecialchars($afterPath); ?>" 
                                             alt="After" 
                                             class="transformation-img">
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Client Name Button -->
                            <div class="client-name-wrapper">
                                <div class="client-name-btn">
                                    <?php echo htmlspecialchars($review['client_name']); ?>
                                </div>
                            </div>
                            
                            <!-- Review Text -->
                            <div class="card-body">
                                <?php 
                                $reviewText = $review['review_text'] ?? $review['review'] ?? $review['text'] ?? $review['testimonial'] ?? '';
                                if (!empty($reviewText)): 
                                ?>
                                    <p class="review-text">
                                        <i class="bi bi-quote"></i>
                                        <?php echo nl2br(htmlspecialchars($reviewText)); ?>
                                        <i class="bi bi-quote"></i>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <p class="text-muted fs-5">No transformations yet. Check back soon!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- Enhanced CTA Section -->
<section class="pricing-cta">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Not Sure Which Plan is Right for You?</h2>
            <p class="cta-subtitle">Contact me for a free consultation and let's discuss your fitness goals</p>
            
            <div class="cta-features">
                <div class="cta-feature">
                    <i class="fa-regular fa-comment"></i>
                    <h4>Free Consultation</h4>
                    <p>Discuss your goals</p>
                </div>
                <div class="cta-feature">
                    <i class="fa-regular fa-circle-check"></i>
                    <h4>Personalized Plan</h4>
                    <p>Tailored to your needs</p>
                </div>
                <div class="cta-feature">
                    <i class="fa-solid fa-ranking-star"></i>
                    <h4>Track Progress</h4>
                    <p>See real results</p>
                </div>
            </div>
            
            <div class="hero-buttons">
                <a href="pricing.php" class="btn btn-primary btn-lg">Get Started</a>
                <a href="contact.php" class="btn btn-outline-light btn-lg">Contact Me</a>
            </div>
        </div>
    </div>
</section>


<?php include 'components/footer.php'; ?>
