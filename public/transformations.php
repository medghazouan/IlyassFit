<?php
require_once '../includes/config/db_config.php';

// Fetch approved reviews from database
$stmt = $pdo->prepare("SELECT * FROM reviews WHERE status = 'approved' ORDER BY id DESC");
$stmt->execute();
$reviews = $stmt->fetchAll();

$pageTitle = "Transformations";
include 'components/meta.php';
include 'components/header.php';
?>

<!-- Hero Section -->
<section class="transformations-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">CLIENT TRANSFORMATIONS</h1>
        <p class="hero-subtitle">Real Results, Real People</p>
    </div>
</section>

<!-- Transformations Section -->
<section class="transformations-section">
    <div class="container py-5">
        <!-- Section Title -->
        <div class="text-center mb-5">
            <h2 class="transformations-title">SUCCESS STORIES</h2>
            <p class="transformations-subtitle">See the amazing transformations of our clients</p>
        </div>
        
        <div class="row g-4 justify-content-center">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="col-md-6 col-lg-4">
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
                                    <img src="<?php echo htmlspecialchars($beforePath); ?>" 
                                         alt="Before" 
                                         class="transformation-img">
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
                                         class="transformation-img">
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

<?php include 'components/footer.php'; ?>
