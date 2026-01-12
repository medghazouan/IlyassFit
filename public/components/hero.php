<?php
/**
 * Hero Component
 * 
 * Variables expected:
 * @var string $heroTitle       Title of the hero section
 * @var string $heroSubtitle    Subtitle of the hero section (optional)
 * @var string $heroBackground  CSS class for background (e.g., 'hero-bg-home', 'hero-bg-contact')
 *                              If 'hero-bg-video' is used, it renders the video background.
 * @var string $heroButtons     HTML content for buttons (optional)
 */

$title = $heroTitle ?? '';
$subtitle = $heroSubtitle ?? '';
$bgClass = $heroBackground ?? '';
?>

<section class="hero-section <?php echo htmlspecialchars($bgClass); ?>">
    
    <?php if ($bgClass === 'hero-bg-video'): ?>
        <div class="hero-video-container">
            <video class="hero-video" autoplay muted loop playsinline>
                <source src="assets/video/hero.webm" type="video/webm">
                <source src="assets/video/hero.mp4" type="video/mp4">
            </video>
        </div>
    <?php endif; ?>

    <div class="hero-overlay"></div>
    
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo $title; ?></h1>
            
            <?php if (!empty($subtitle)): ?>
                <p class="hero-subtitle"><?php echo $subtitle; ?></p>
            <?php endif; ?>

            <?php if (isset($heroButtons) && !empty($heroButtons)): ?>
                <div class="hero-buttons">
                    <?php echo $heroButtons; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
