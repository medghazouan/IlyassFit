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
                <!-- Decorative Icon -->
                <div class="about-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path
                            d="M349.4 44.6c5.9-13.7 1.5-29.7-10.6-38.5s-28.6-8-39.9 1.8l-256 224c-10 8.8-13.6 22.9-8.9 35.3S50.7 288 64 288H175.5L98.6 467.4c-5.9 13.7-1.5 29.7 10.6 38.5s28.6 8 39.9-1.8l256-224c10-8.8 13.6-22.9 8.9-35.3s-16.6-20.7-30-20.7H272.5L349.4 44.6z" />
                    </svg>
                </div>
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


<!-- Gallery Section -->
<!-- Gallery section -->

    <div class="container my-5">
    <div class="row g-4">
        <div class="col-6 col-md-3">
            <div class="d-flex flex-column gap-4">
                <div>
                    <img class="img-fluid rounded" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image.jpg" alt="">
                </div>
                <div>
                    <img class="img-fluid rounded" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-1.jpg" alt="">
                </div>
                <div>
                    <img class="img-fluid rounded" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-2.jpg" alt="">
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="d-flex flex-column gap-4">
                <div>
                    <img class="img-fluid rounded" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-3.jpg" alt="">
                </div>
                <div>
                    <img class="img-fluid rounded" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-4.jpg" alt="">
                </div>
                <div>
                    <img class="img-fluid rounded" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-5.jpg" alt="">
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="d-flex flex-column gap-4">
                <div>
                    <img class="img-fluid rounded" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-6.jpg" alt="">
                </div>
                <div>
                    <img class="img-fluid rounded" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-7.jpg" alt="">
                </div>
                <div>
                    <img class="img-fluid rounded" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-8.jpg" alt="">
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="d-flex flex-column gap-4">
                <div>
                    <img class="img-fluid rounded" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-9.jpg" alt="">
                </div>
                <div>
                    <img class="img-fluid rounded" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-10.jpg" alt="">
                </div>
                <div>
                    <img class="img-fluid rounded" src="https://flowbite.s3.amazonaws.com/docs/gallery/masonry/image-11.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
</div>


<?php
// Include footer
include 'components/footer.php';
?>