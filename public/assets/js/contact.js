document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('contactForm');
    const alertContainer = document.getElementById('alertContainer');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Stop page refresh

            const formData = new FormData(form);
            const submitBtn = form.querySelector('.btn-submit');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Show loading
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-block';

            // Send AJAX request
            fetch('process_contact.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    // Hide loading
                    submitBtn.disabled = false;
                    btnText.style.display = 'inline-block';
                    btnLoading.style.display = 'none';

                    if (data.success) {
                        // Show creative success modal
                        showSuccessModal(data.firstName);
                        form.reset();
                    } else {
                        // Show error alert
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    btnText.style.display = 'inline-block';
                    btnLoading.style.display = 'none';
                    showAlert('danger', 'An error occurred. Please try again.');
                    console.error('Error:', error);
                });
        });
    }

    function showSuccessModal(firstName) {
        // Clear any existing alerts
        if (alertContainer) {
            alertContainer.innerHTML = '';
        }

        // Create overlay and modal
        const overlay = document.createElement('div');
        overlay.id = 'successOverlay';
        overlay.innerHTML = `
            <div class="success-modal">
                <button class="modal-close-x" onclick="closeSuccessModal()">
                    <i class="fas fa-times"></i>
                </button>
                <div class="success-icon">
                    <svg viewBox="0 0 52 52" class="checkmark">
                        <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                </div>
                <h2 class="success-title">Amazing, ${firstName}!</h2>
                <p class="success-message">
                    Your transformation journey starts now! I've received your message and I'm already excited to help you crush your fitness goals.
                </p>
                <p class="success-note">
                    <i class="fas fa-clock"></i> Expect a personal response within 24 hours
                </p>
                <button class="success-close-btn" onclick="closeSuccessModal()">
                    Continue
                </button>
            </div>
        `;

        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            #successOverlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.9);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 99999;
                animation: fadeIn 0.3s ease;
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            .success-modal {
                background: linear-gradient(145deg, #1a1a2e, #16213e);
                border: 2px solid #fc0404;
                border-radius: 20px;
                padding: 40px;
                text-align: center;
                max-width: 450px;
                margin: 20px;
                animation: slideUp 0.5s ease;
                box-shadow: 0 20px 60px rgba(252,4,4,0.3);
            }
            @keyframes slideUp {
                from { transform: translateY(50px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            .success-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 20px;
            }
            .checkmark {
                width: 80px;
                height: 80px;
            }
            .checkmark-circle {
                stroke: #fc0404;
                stroke-width: 2;
                stroke-dasharray: 166;
                stroke-dashoffset: 166;
                animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
            }
            .checkmark-check {
                stroke: #fc0404;
                stroke-width: 2;
                stroke-dasharray: 48;
                stroke-dashoffset: 48;
                animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
            }
            @keyframes stroke {
                100% { stroke-dashoffset: 0; }
            }
            .success-title {
                color: #fff;
                font-family: 'Montserrat', sans-serif;
                font-size: 1.8rem;
                font-weight: 800;
                margin-bottom: 15px;
            }
            .success-message {
                color: #b0b0b0;
                font-size: 1rem;
                line-height: 1.6;
                margin-bottom: 20px;
            }
            .success-note {
                color: #fc0404;
                font-size: 0.9rem;
                margin-bottom: 25px;
            }
            .success-note i {
                margin-right: 8px;
            }
            .success-close-btn {
                background: linear-gradient(135deg, #fc0404, #ff2020);
                color: #fff;
                border: none;
                padding: 15px 40px;
                border-radius: 50px;
                font-family: 'Montserrat', sans-serif;
                font-weight: 700;
                font-size: 1rem;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .success-close-btn:hover {
                transform: scale(1.05);
                box-shadow: 0 10px 30px rgba(252,4,4,0.4);
            }
            .success-close-btn i {
                margin-left: 10px;
            }
            .modal-close-x {
                position: absolute;
                top: 15px;
                right: 15px;
                background: transparent;
                border: 2px solid rgba(255,255,255,0.3);
                color: #fff;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                font-size: 1.2rem;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .modal-close-x:hover {
                background: #fc0404;
                border-color: #fc0404;
                transform: rotate(90deg);
            }
            .success-modal {
                position: relative;
            }
        `;

        document.head.appendChild(style);
        document.body.appendChild(overlay);
        document.body.style.overflow = 'hidden';

        // Close function
        window.closeSuccessModal = function () {
            overlay.style.animation = 'fadeIn 0.3s ease reverse';
            setTimeout(() => {
                overlay.remove();
                style.remove();
                document.body.style.overflow = '';
            }, 300);
        };

        // Close on overlay click
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) {
                closeSuccessModal();
            }
        });
    }

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';

        alertContainer.innerHTML = `
            <div class="alert ${alertClass}">
                <i class="bi ${icon}"></i> ${message}
            </div>
        `;

        alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        if (type === 'success') {
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(() => alertContainer.innerHTML = '', 300);
                }
            }, 5000);
        }
    }
});
