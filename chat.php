
<?php
// Prevent any accidental output before JSON headers
if (ob_get_level() === 0) {
    ob_start();
}

header_remove();
http_response_code(200);
header('Content-Type: application/json; charset=utf-8');

function sendJson($reply, $status = 200) {
    http_response_code($status);

    if (ob_get_length()) {
        ob_clean();
    }

    echo json_encode(
        ['reply' => $reply],
        JSON_UNESCAPED_UNICODE
    );
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJson('POST requests only.', 405);
}

$config = __DIR__ . '/config.php';

if (!file_exists($config)) {
    sendJson('Server configuration is missing.', 500);
}

require $config;

if (empty($groqApiKey)) {
    sendJson('Groq API key is missing.', 500);
}

if (!function_exists('curl_init')) {
    sendJson('PHP cURL extension is not enabled.', 500);
}

$input = json_decode(file_get_contents('php://input'), true);

$message = $input['message'] ?? '';
$category = $input['category'] ?? '';

if (!is_string($message) || !is_string($category)) {
    sendJson('Invalid request.', 400);
}

$message = trim($message);

if ($message === '' || strlen($message) > 5000) {
    sendJson('Invalid message.', 400);
}

$prompts = [
    'IT' => 'You are an IT and programming assistant. Answer only IT and programming questions.',
    'Medical' => 'You provide general human health information. Never diagnose or prescribe personalized medication.',
    'ShareMarket' => 'You are a share market education assistant. Never guarantee profits or provide personalized financial advice.'
];

if (!isset($prompts[$category])) {
    sendJson('Invalid category.', 400);
}

$payload = [
    'model' => $groqModel,
    'messages' => [
        ['role' => 'system', 'content' => $prompts[$category]],
        ['role' => 'user', 'content' => $message]
    ],
    'temperature' => 0.3,
    'max_tokens' => 500
];

$ch = curl_init($groqApiUrl);

curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $groqApiKey
    ],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 60
]);

$result = curl_exec($ch);
$error = curl_error($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if ($result === false) {
    error_log('Groq error: ' . $error);
    sendJson('Unable to connect to AI service.', 502);
}

$data = json_decode($result, true);

if ($status < 200 || $status >= 300) {
    error_log('Groq HTTP error: ' . $status . ' ' . $result);
    sendJson('Groq API error. Check server logs.', 502);
}

$reply = $data['choices'][0]['message']['content'] ?? '';

if (!is_string($reply) || trim($reply) === '') {
    sendJson('AI returned an empty response.', 502);
}

sendJson(trim($reply));
