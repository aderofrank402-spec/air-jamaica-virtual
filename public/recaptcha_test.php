<?php
$site_key = '6LcLm9crAAAAADsw3enUYYNOM4G2Lmfv0VbbQzdr'; // Hardcoded for this test
$secret_key = '6LcLm9crAAAAAA_xZS2uidgyfh5YELRbAfZXiFz_'; // Hardcoded for this test
$captcha_enabled = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reCAPTCHA Test Page</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .section { margin-bottom: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #333; }
        h2 { color: #555; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 3px; overflow-x: auto; }
        .success { color: green; }
        .error { color: red; }
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="container">
        <h1>reCAPTCHA Test Page</h1>
        
        <div class="section">
            <h2>Configuration</h2>
            <p><strong>Site Key:</strong> <?php echo empty($site_key) ? '<span class="error">Not set</span>' : '<span class="success">' . substr($site_key, 0, 6) . '...' . substr($site_key, -4) . '</span>'; ?></p>
            <p><strong>Secret Key:</strong> <?php echo empty($secret_key) ? '<span class="error">Not set</span>' : '<span class="success">Properly configured</span>'; ?></p>
            <p><strong>Enabled:</strong> <?php echo $captcha_enabled ? '<span class="success">Yes</span>' : '<span class="error">No</span>'; ?></p>
        </div>
        
        <div class="section">
            <h2>Test reCAPTCHA Widget</h2>
            <form id="testForm" action="recaptcha_verify.php" method="post">
                <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($site_key); ?>"></div>
                <br>
                <button type="submit">Submit</button>
            </form>
        </div>
        
        <div class="section">
            <h2>Troubleshooting</h2>
            <p><strong>1. Check if JavaScript is enabled in your browser</strong></p>
            <p><strong>2. Check if the reCAPTCHA domain is allowed</strong> in the Google reCAPTCHA admin console. Make sure you've added the domain where you're testing (including localhost if testing locally).</p>
            <p><strong>3. Check your browser console</strong> for any JavaScript errors.</p>
            <p><strong>4. Verify your .env file</strong> has the correct RECAPTCHA_SITE_KEY and RECAPTCHA_SECRET_KEY values.</p>
        </div>
        
        <div class="section">
            <h2>Debug Information</h2>
            <pre id="debugInfo">Waiting for reCAPTCHA to load...</pre>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                var debugInfo = document.getElementById('debugInfo');
                var captchaDiv = document.querySelector('.g-recaptcha');
                
                if (captchaDiv) {
                    if (captchaDiv.querySelector('iframe')) {
                        debugInfo.textContent = 'SUCCESS: reCAPTCHA widget loaded successfully!';
                        debugInfo.style.color = 'green';
                    } else {
                        debugInfo.textContent = 'ERROR: reCAPTCHA widget failed to load.\nPossible issues:\n1. Invalid site key\n2. Domain not allowed in reCAPTCHA settings\n3. Network connectivity issues\n4. Content Security Policy restrictions';
                        debugInfo.style.color = 'red';
                    }
                } else {
                    debugInfo.textContent = 'ERROR: reCAPTCHA container not found on page.';
                    debugInfo.style.color = 'red';
                }
            }, 3000);
        });
    </script>
</body>
</html>