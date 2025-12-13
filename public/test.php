<?php
// Set page title
$pageTitle = "Test Page";

// Include meta and header
include 'components/meta.php';

include 'components/header.php';


?>

<!-- Main Content -->
<div class="container section-padding">
    <div class="row">
        <div class="col-12 text-center">
            <h1 class="section-title">Welcome to Ilyass Fit</h1>
            <p class="section-subtitle">This is a test page to verify header and footer components</p>
        </div>
    </div>


<!-- About -->
<!-- About Me Section -->
<section class="about-me-section">
    <div class="container">
        <div class="about-me-container">
            <!-- Image Side -->
            <div class="about-me-image-wrapper">
                <!-- Decorative Icon -->
                <div class="about-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path d="M349.4 44.6c5.9-13.7 1.5-29.7-10.6-38.5s-28.6-8-39.9 1.8l-256 224c-10 8.8-13.6 22.9-8.9 35.3S50.7 288 64 288H175.5L98.6 467.4c-5.9 13.7-1.5 29.7 10.6 38.5s28.6 8 39.9-1.8l256-224c10-8.8 13.6-22.9 8.9-35.3s-16.6-20.7-30-20.7H272.5L349.4 44.6z"/>
                    </svg>
                </div>
                <img src="assets/images/static/about_me.jpg" alt="Ilyass - Personal Trainer" class="about-me-image">
            </div>

            <!-- Content Side -->
            <div class="about-me-content">
                <h1 class="about-me-title">ABOUT ME</h1>
                
                <p class="about-me-text">
                    Ilyass started as a passionate fitness enthusiast in Marrakech, aiming to help people transform their lives through proper training and nutrition. What began as a personal journey soon became a mission to guide others beyond their limits and help them achieve their dream physique.
                </p>
                
                <p class="about-me-text">
                    Currently, I offer personalized training programs, nutrition coaching, and group classes to help my clients find their strongest, healthiest selves. I believe in a holistic approach to fitness - combining strength training, cardio, flexibility, and mindset coaching to create lasting transformations.
                </p>
                
                <p class="about-me-text">
                    My philosophy is simple: consistency beats perfection. Whether you're just starting out or looking to break through plateaus, I'm here to support you every step of the way. Together, we'll build not just a better body, but a stronger mindset and healthier lifestyle.
                </p>
            </div>
        </div>
    </div>
</section>



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



    <div class="row mt-5">
        <div class="col-md-6">
            <h3>Test Section 1</h3>
            <p>This is some sample content to test the layout. The header should appear at the top with the navigation
                menu, and the footer should appear at the bottom with contact information and map.</p>
            <button class="btn btn-primary mt-3">Primary Button</button>
        </div>
        <div class="col-md-6">
            <h3>Test Section 2</h3>
            <p>Check that all fonts are loading correctly (Inter for body text, Montserrat for headings). Also verify
                that the brand colors (#fc0404, #1b1f22, #212529) are displaying properly.</p>
            <button class="btn btn-outline-primary mt-3">Outline Button</button>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="bg-light-gray p-4" style="border-radius: 10px;">
                <h3 class="text-red">Features to Check:</h3>
                <ul style="color: var(--text-gray);">
                    <li>✓ Header with logo and navigation links</li>
                    <li>✓ Active link highlighting</li>
                    <li>✓ Responsive navbar (test on mobile)</li>
                    <li>✓ Footer with contact info and map</li>
                    <li>✓ Social media icons</li>
                    <li>✓ Brand colors and fonts</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include 'components/footer.php';
?>