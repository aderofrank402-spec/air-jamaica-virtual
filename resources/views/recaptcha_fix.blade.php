<!-- This script directly injects the reCAPTCHA on the registration page -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to inject reCAPTCHA
    function injectRecaptcha() {
        var recaptchaContainer = document.querySelector('.g-recaptcha');
        if (!recaptchaContainer) {
            console.log("No reCAPTCHA container found");
            return;
        }
        
        // If container exists but no iframe inside it (meaning reCAPTCHA hasn't rendered)
        if (!recaptchaContainer.querySelector('iframe')) {
            console.log("Container found but no iframe - attempting to fix");
            
            // Try to add the script directly
            var recaptchaScript = document.createElement('script');
            recaptchaScript.src = 'https://www.google.com/recaptcha/api.js';
            recaptchaScript.async = true;
            recaptchaScript.defer = true;
            document.head.appendChild(recaptchaScript);
            
            // Add a status message
            var statusDiv = document.getElementById('recaptcha-status');
            if (statusDiv) {
                statusDiv.innerHTML = 'Fixing reCAPTCHA... Please wait a moment.';
                statusDiv.style.color = '#ffaa00';
            }
            
            // Check again after script should have loaded
            setTimeout(function() {
                if (!recaptchaContainer.querySelector('iframe')) {
                    console.log("Still not loaded after fix attempt");
                    if (statusDiv) {
                        statusDiv.innerHTML = 'Could not load reCAPTCHA. Please try a different browser or disable extensions.';
                        statusDiv.style.color = '#ff4444';
                    }
                } else {
                    console.log("Fix successful!");
                    if (statusDiv) {
                        statusDiv.style.display = 'none';
                    }
                }
            }, 3000);
        } else {
            console.log("reCAPTCHA already loaded properly");
            var statusDiv = document.getElementById('recaptcha-status');
            if (statusDiv) {
                statusDiv.style.display = 'none';
            }
        }
    }
    
    // Call the function
    injectRecaptcha();
});
</script>