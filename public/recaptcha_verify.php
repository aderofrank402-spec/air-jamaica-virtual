<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$secret_key = env('RECAPTCHA_SECRET_KEY', '');
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
$success = false;
$error_message = '';

if (empty($recaptcha_response)) {
    $error_message = 'reCAPTCHA response is missing';
} else {
    try {
        $client = new GuzzleHttp\Client();
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => $secret_key,
                'response' => $recaptcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR'],
            ]
        ]);
        
        $result = json_decode($response->getBody()->getContents(), true);
        $success = isset($result['success']) && $result['success'] === true;
        
        if (!$success && isset($result['error-codes'])) {
            $error_message = 'reCAPTCHA verification failed: ' . implode(', ', $result['error-codes']);
        }
    } catch (Exception $e) {
        $error_message = 'Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reCAPTCHA Verification Result</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .section { margin-bottom: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #333; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 3px; overflow-x: auto; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .back-link { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>reCAPTCHA Verification Result</h1>
        
        <div class="section">
            <h2>Result</h2>
            <?php if ($success): ?>
                <p class="success">SUCCESS: reCAPTCHA verification passed!</p>
            <?php else: ?>
                <p class="error">ERROR: reCAPTCHA verification failed!</p>
                <?php if ($error_message): ?>
                    <p><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <div class="section">
            <h2>Debug Information</h2>
            <pre>
reCAPTCHA Response: <?php echo !empty($recaptcha_response) ? substr($recaptcha_response, 0, 20) . '...' : 'Empty'; ?>
            </pre>
        </div>
        
        <div class="back-link">
            <a href="recaptcha_test.php">&laquo; Back to Test Page</a>
        </div>
    </div>
</body>
</html>