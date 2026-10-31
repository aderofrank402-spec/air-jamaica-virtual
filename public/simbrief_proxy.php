<?php
// simbrief_proxy.php
// Simple server-side proxy to post form data to external SimBrief endpoints
// Place this file in the public/ directory and open /simbrief_debug.html

function getRawBodyFromRequest() {
    // If raw_body provided, use it
    if (!empty($_POST['raw_body'])) {
        return $_POST['raw_body'];
    }

    // Otherwise build urlencoded body from POST fields excluding 'endpoint' and 'raw_body'
    $fields = $_POST;
    unset($fields['endpoint']);
    unset($fields['raw_body']);

    return http_build_query($fields);
}

$endpoint = $_POST['endpoint'] ?? '';
if (empty($endpoint)) {
    echo "<h3>No endpoint provided</h3>";
    exit;
}

$body = getRawBodyFromRequest();

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_HEADER, true); // include headers in output
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$errno = curl_errno($ch);
$errmsg = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$bodyResp = substr($response, $headerSize);

curl_close($ch);

header('Content-Type: text/html; charset=utf-8');
echo '<h2>SimBrief Proxy Result</h2>';
echo '<p><strong>Endpoint:</strong> ' . htmlspecialchars($endpoint) . '</p>';
echo '<p><strong>HTTP Status:</strong> ' . htmlspecialchars($httpCode) . '</p>';
if ($errno) {
    echo '<p><strong>cURL error:</strong> ' . htmlspecialchars($errmsg) . ' (' . $errno . ')</p>';
}

echo '<h3>Request Body</h3>';
echo '<pre>' . htmlspecialchars($body) . '</pre>';

echo '<h3>Response Headers</h3>';
echo '<pre>' . htmlspecialchars($headers) . '</pre>';

echo '<h3>Response Body</h3>';
echo '<pre>' . htmlspecialchars($bodyResp) . '</pre>';

echo '<p>Copy the status/headers/body and paste them back here for analysis.</p>';
