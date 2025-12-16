<?php 
// Set page title
$pageTitle = "Pricing Plans";

// Include database config
require_once '../includes/config/db_config.php';

// Fetch pricing plans
$face_to_face_query = "SELECT * FROM pricing_plans WHERE coaching_type = 'face_to_face' AND is_active = 1 ORDER BY display_order ASC";
$online_query = "SELECT * FROM pricing_plans WHERE coaching_type = 'online' AND is_active = 1 ORDER BY display_order ASC";

$face_to_face_plans = $pdo->query($face_to_face_query);
$online_plans = $pdo->query($online_query);

// Include meta and header
include 'components/meta.php';
include 'components/header.php';
?>

<!-- Pricing Hero Section -->
<section class="pricing-hero">
    <div class="container">
        <h1 class="section-title">Choose Your Transformation Path</h1>
        <p class="section-subtitle">Invest in yourself. Choose the plan that fits your goals and lifestyle.</p>
    </div>
</section>

<!-- Face-to-Face Coaching Plans -->
<section class="pricing-section section-padding">
    <div class="container">
        <div class="section-header">
            <h2 class="coaching-type-title">
                <i class="fas fa-dumbbell"></i> Face-to-Face Coaching Plans
            </h2>
            <p class="coaching-type-subtitle">Train with me in person at our Marrakech facility</p>
        </div>
        
        <div class="row justify-content-center g-5">
            <?php while($plan = $face_to_face_plans->fetch()): ?>
                <div class="col-lg-5 col-md-6">
                    <div class="pricing-card <?php echo strtolower($plan['plan_name']) == 'premium' ? 'featured' : ''; ?>">
                        <?php if(strtolower($plan['plan_name']) == 'premium'): ?>
                            <div class="badge-featured">Most Popular</div>
                        <?php endif; ?>
                        
                        <div class="plan-header">
                            <h3 class="plan-name"><?php echo htmlspecialchars($plan['plan_name']); ?></h3>
                            <?php if($plan['description']): ?>
                                <span class="plan-badge"><?php echo htmlspecialchars($plan['description']); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="plan-price">
                            <span class="currency">MAD</span>
                            <span class="amount"><?php echo number_format($plan['price'], 0); ?></span>
                            <span class="duration">/ <?php echo htmlspecialchars($plan['duration']); ?></span>
                        </div>
                        
                        <div class="plan-features">
                            <?php 
                            $features = explode("\n", $plan['features']);
                            foreach($features as $feature): 
                                if(trim($feature)):
                            ?>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span><?php echo htmlspecialchars(trim($feature)); ?></span>
                                </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                        
                        <a href="<?php echo $plan['booking_url'] ?? 'contact.php'; ?>" class="btn btn-primary btn-lg">
                            <?php echo $plan['button_text'] ?? 'Get Started'; ?>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Online Coaching Plans -->
<section class="pricing-section section-padding bg-light-gray">
    <div class="container">
        <div class="section-header">
            <h2 class="coaching-type-title">
                <i class="fa-solid fa-earth-americas"></i> Online Coaching Plans
            </h2>
            <p class="coaching-type-subtitle">Train anywhere, anytime with personalized online coaching</p>
        </div>
        
        <div class="row justify-content-center g-5">
            <?php while($plan = $online_plans->fetch()): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card <?php echo strtolower($plan['plan_name']) == 'gold' ? 'featured' : ''; ?>">
                        <?php if(strtolower($plan['plan_name']) == 'gold'): ?>
                            <div class="badge-featured">Recommended</div>
                        <?php endif; ?>
                        
                        <div class="plan-header">
                            <h3 class="plan-name"><?php echo htmlspecialchars($plan['plan_name']); ?></h3>
                            <?php if($plan['description']): ?>
                                <span class="plan-badge"><?php echo htmlspecialchars($plan['description']); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="plan-price">
                            <span class="currency">MAD</span>
                            <span class="amount"><?php echo number_format($plan['price'], 0); ?></span>
                            <span class="duration">/ <?php echo htmlspecialchars($plan['duration']); ?></span>
                        </div>
                        
                        <div class="plan-features">
                            <?php 
                            $features = explode("\n", $plan['features']);
                            foreach($features as $feature): 
                                if(trim($feature)):
                            ?>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span><?php echo htmlspecialchars(trim($feature)); ?></span>
                                </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                        
                        <a href="<?php echo $plan['booking_url'] ?? 'contact.php'; ?>" class="btn btn-primary btn-lg">
                            <?php echo $plan['button_text'] ?? 'Get Started'; ?>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
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
                <a href="contact.php" class="btn btn-primary btn-lg">Contact Me Now</a>
            </div>
        </div>
    </div>
</section>

<?php 
// Include footer
include 'components/footer.php';
?>