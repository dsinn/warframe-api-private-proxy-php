<?php
if (($_SERVER['HTTP_X_PRIVATE_PROXY_SECRET'] ?? '') !== ($_SERVER['PRIVATE_PROXY_SECRET'] ?? '')) {
    http_response_code(403);
    exit;
}

$url = $_GET['url'] ?? '';
if (!$url) {
    http_response_code(400);
    exit;
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response === false || $httpCode !== 200) {
    http_response_code(502);
    exit;
}

header('Content-Type: application/json');
header('Cache-Control: public, max-age=60');
echo $response;
