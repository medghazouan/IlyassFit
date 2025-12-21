/**
 * Main JavaScript file for IlyassFit
 * Handles animations, sliders, and interactive elements on homepage
 */

// ============================================
// 1. ABOUT SECTION - Scroll Animations & Testimonials
// ============================================
document.addEventListener('DOMContentLoaded', function () {
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
                    const bgImage = document.querySelector('.about-background-image');
                    if (bgImage) {
                        bgImage.classList.add('visible');
                    }
                }
            }
        });
    }, observerOptions);

    const animatedElements = document.querySelectorAll('.slide-in-right, .slide-in-left');
    animatedElements.forEach(el => observer.observe(el));

    // Observer for the section itself to trigger background animation
    const section = document.querySelector('.about-me-section');
    if (section) observer.observe(section);

    // Testimonial Slider
    const reviews = document.querySelectorAll('.review-item');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.getElementById('prevReview');
    const nextBtn = document.getElementById('nextReview');
    let currentIndex = 0;

    function showReview(index) {
        reviews.forEach(review => review.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        if (reviews[index]) {
            reviews[index].classList.add('active');
        }
        if (dots[index]) {
            dots[index].classList.add('active');
        }
    }

    if (prevBtn && nextBtn && reviews.length > 0) {
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

// ============================================
// 2. SERVICES SECTION - Auto Slider
// ============================================
(function () {
    let currentServiceIndex = 0;
    const serviceSlides = document.querySelectorAll('.service-slide');
    const serviceDots = document.querySelectorAll('.service-dot');
    const totalServices = serviceSlides.length;
    let serviceInterval;

    function showService(index) {
        // Reset all
        serviceSlides.forEach(slide => {
            slide.classList.remove('active');
            slide.style.opacity = '0';
            slide.style.transform = 'translateY(20px)';
        });
        serviceDots.forEach(dot => dot.classList.remove('active'));

        // Activate current
        if (serviceSlides[index]) {
            serviceSlides[index].classList.add('active');
            // Slight delay for animation effect
            setTimeout(() => {
                serviceSlides[index].style.opacity = '1';
                serviceSlides[index].style.transform = 'translateY(0)';
            }, 50);
        }
        if (serviceDots[index]) {
            serviceDots[index].classList.add('active');
        }

        currentServiceIndex = index;
    }

    function nextService() {
        let nextIndex = (currentServiceIndex + 1) % totalServices;
        showService(nextIndex);
    }

    // Make goToService global for onclick attribute
    window.goToService = function (index) {
        clearInterval(serviceInterval); // Pause auto-slide on interaction
        showService(index);
        startServiceSlider(); // Restart
    };

    function startServiceSlider() {
        clearInterval(serviceInterval);
        serviceInterval = setInterval(nextService, 5000); // Change every 5 seconds
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        // Initial state for first slide
        if (serviceSlides.length > 0) {
            serviceSlides[0].style.opacity = '1';
            serviceSlides[0].style.transform = 'translateY(0)';
            startServiceSlider();
        }
    });
})();

// ============================================
// 3. VIDEO MODAL - Gallery Section
// ============================================
document.addEventListener('DOMContentLoaded', function () {
    const videoModal = document.getElementById('videoModal');
    const openVideoBtn = document.getElementById('openVideoModal');
    const closeVideoBtn = document.getElementById('closeVideoModal');
    const videoModalOverlay = document.getElementById('videoModalOverlay');
    const modalVideo = document.getElementById('modalVideo');

    // Open modal (NO AUTOPLAY)
    if (openVideoBtn && videoModal) {
        openVideoBtn.addEventListener('click', function () {
            videoModal.classList.add('active');
            document.body.style.overflow = 'hidden';
            // Removed modalVideo.play() - user must click play manually
        });
    }

    // Close modal function
    function closeModal() {
        if (videoModal) {
            videoModal.classList.remove('active');
            document.body.style.overflow = '';
        }
        if (modalVideo) {
            modalVideo.pause();
            modalVideo.currentTime = 0;
        }
    }

    // Close button
    if (closeVideoBtn) {
        closeVideoBtn.addEventListener('click', closeModal);
    }

    // Click outside to close
    if (videoModalOverlay) {
        videoModalOverlay.addEventListener('click', closeModal);
    }

    // ESC key to close
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && videoModal && videoModal.classList.contains('active')) {
            closeModal();
        }
    });

    // Prevent video controls from closing modal
    if (modalVideo) {
        modalVideo.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }
});

// =======================================
// 1. Pricing SECTION - Scroll Animations 
// =======================================

document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.pricing-card');

    if (!cards.length) return;

    // If no explicit direction class, alternate them in JS
    cards.forEach((card, index) => {
        if (!card.classList.contains('from-left') && !card.classList.contains('from-right')) {
            const dirClass = index % 2 === 0 ? 'from-right' : 'from-left';
            card.classList.add(dirClass);
        }
    });

    const observerOptions = {
        threshold: 0.2
    };

    const onIntersect = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                // Remove this if you want the animation every time on scroll
                observer.unobserve(entry.target);
            }
        });
    };

    const observer = new IntersectionObserver(onIntersect, observerOptions);

    cards.forEach(card => observer.observe(card));
});

document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.pricing-card');

    // Quick debug
    console.log('Pricing cards found:', cards.length);

    if (!cards.length) return;

    // Alternate directions if not set in PHP
    cards.forEach((card, index) => {
        if (!card.classList.contains('from-left') &&
            !card.classList.contains('from-right')) {
            card.classList.add(index % 2 === 0 ? 'from-right' : 'from-left');
        }
    });

    // Fallback for old browsers
    if (!('IntersectionObserver' in window)) {
        cards.forEach(card => card.classList.add('visible'));
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                obs.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.2
    });

    cards.forEach(card => observer.observe(card));
});
