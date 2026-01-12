document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const alertContainer = document.getElementById('alertContainer');
    
    if (form) {
        form.addEventListener('submit', function(e) {
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
                
                // Show message
                showAlert(data.success ? 'success' : 'danger', data.message);
                
                // Clear form if success
                if (data.success) {
                    form.reset();
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
