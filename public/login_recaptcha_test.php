<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login reCAPTCHA Test</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1 {
            color: #00a651;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #00a651;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #008f45;
        }
        .status {
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login reCAPTCHA Test</h1>
        
        <div class="status" id="config-status">
            <?php
            require __DIR__.'/../vendor/autoload.php';
            $app = require_once __DIR__.'/../bootstrap/app.php';
            $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
            
            $enabled = setting('captcha.enabled', false);
            $siteKey = setting('captcha.site_key', '');
            $secretKey = setting('captcha.secret_key', '');
            
            if ($enabled && !empty($siteKey) && !empty($secretKey)) {
                echo '<div class="success">reCAPTCHA is properly configured</div>';
                echo '<p><strong>Site Key:</strong> ' . htmlspecialchars($siteKey) . '</p>';
            } else {
                echo '<div class="error">reCAPTCHA is not properly configured</div>';
                echo '<p><strong>Enabled:</strong> ' . ($enabled ? 'Yes' : 'No') . '</p>';
                echo '<p><strong>Site Key:</strong> ' . (!empty($siteKey) ? 'Set' : 'Not set') . '</p>';
                echo '<p><strong>Secret Key:</strong> ' . (!empty($secretKey) ? 'Set' : 'Not set') . '</p>';
            }
            ?>
        </div>
        
        <form id="login-form" action="javascript:void(0);" onsubmit="testForm()">
            <div class="form-group">
                <label for="email">Email or Pilot ID:</label>
                <input type="text" id="email" name="email" placeholder="Enter your email or pilot ID" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <div class="form-group">
                <label>reCAPTCHA:</label>
                <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($siteKey); ?>"></div>
            </div>
            
            <button type="submit">Test Login Form</button>
        </form>
        
        <div class="status" id="form-result" style="display: none;"></div>
    </div>
    
    <div class="container">
        <h2>Login Form Implementation Check</h2>
        <div id="login-form-check">
            <?php
            $loginFormPath = base_path('resources/views/auth/login.blade.php');
            $loginControllerPath = base_path('app/Http/Controllers/Auth/LoginController.php');
            
            if (file_exists($loginFormPath) && file_exists($loginControllerPath)) {
                $loginFormContent = file_get_contents($loginFormPath);
                $loginControllerContent = file_get_contents($loginControllerPath);
                
                $hasRecaptchaInForm = strpos($loginFormContent, 'g-recaptcha') !== false;
                $hasRecaptchaValidation = strpos($loginControllerContent, 'g-recaptcha-response') !== false;
                
                if ($hasRecaptchaInForm && $hasRecaptchaValidation) {
                    echo '<div class="success">reCAPTCHA is properly implemented in the login form and controller</div>';
                } else {
                    echo '<div class="error">reCAPTCHA implementation is incomplete</div>';
                    echo '<p><strong>In Login Form:</strong> ' . ($hasRecaptchaInForm ? 'Yes' : 'No') . '</p>';
                    echo '<p><strong>In Login Controller:</strong> ' . ($hasRecaptchaValidation ? 'Yes' : 'No') . '</p>';
                }
            } else {
                echo '<div class="error">Could not find login form or controller files</div>';
            }
            ?>
        </div>
        
        <h3>Links</h3>
        <ul>
            <li><a href="/login">Go to actual login page</a></li>
            <li><a href="/recaptcha_test.php">Go to reCAPTCHA test page</a></li>
            <li><a href="/register">Go to registration page</a></li>
        </ul>
    </div>
    
    <script>
        function testForm() {
            const response = grecaptcha.getResponse();
            const formResult = document.getElementById('form-result');
            
            formResult.style.display = 'block';
            
            if (response.length === 0) {
                formResult.className = 'status error';
                formResult.innerHTML = '<strong>Error:</strong> Please complete the reCAPTCHA';
            } else {
                formResult.className = 'status success';
                formResult.innerHTML = '<strong>Success!</strong> reCAPTCHA completed successfully. In a real form, this would be validated server-side.';
            }
        }
        
        // Check if reCAPTCHA is loaded properly
        window.addEventListener('load', function() {
            setTimeout(function() {
                const recaptchaIframe = document.querySelector('.g-recaptcha iframe');
                if (!recaptchaIframe) {
                    const formResult = document.getElementById('form-result');
                    formResult.style.display = 'block';
                    formResult.className = 'status error';
                    formResult.innerHTML = '<strong>Error:</strong> reCAPTCHA failed to load. Try disabling ad blockers or using incognito mode.';
                }
            }, 2000);
        });
    </script>
</body>
</html>