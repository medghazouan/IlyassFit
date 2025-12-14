<?php
// Set page title
$pageTitle = "Home";

// Include meta and header
include 'components/meta.php';
include 'components/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">TRANSFORM YOUR BODY</h1>
            <p class="hero-subtitle">Professional Training | Nutrition Coaching | Results Guaranteed</p>
            <div class="hero-buttons">
                <a href="contact.php" class="btn btn-primary btn-lg">Get Started</a>
                <a href="#about" class="btn btn-outline-light btn-lg">Learn More</a>
            </div>
        </div>
    </div>
</section>

<!-- About Me Section -->
<section class="about-me-section" id="about">
    <div class="container">
        <div class="about-me-container">
            <!-- Image Side -->
            <div class="about-me-image-wrapper">
                <img src="assets/images/static/contact_img1.jpg" alt="Ilyass - Personal Trainer" class="about-me-image">
            </div>

            <!-- Content Side -->
            <div class="about-me-content">
                <h1 class="about-me-title">ABOUT ME</h1>

                <p class="about-me-text">
                    Ilyass started as a passionate fitness enthusiast in Marrakech, aiming to help people transform
                    their lives through proper training and nutrition. What began as a personal journey soon became a
                    mission to guide others beyond their limits and help them achieve their dream physique.
                </p>

                <p class="about-me-text">
                    Currently, I offer personalized training programs, nutrition coaching, and group classes to help my
                    clients find their strongest, healthiest selves. I believe in a holistic approach to fitness -
                    combining strength training, cardio, flexibility, and mindset coaching to create lasting
                    transformations.
                </p>

                <p class="about-me-text">
                    My philosophy is simple: consistency beats perfection. Whether you're just starting out or looking
                    to break through plateaus, I'm here to support you every step of the way. Together, we'll build not
                    just a better body, but a stronger mindset and healthier lifestyle.
                </p>
            </div>
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