
<?php

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    die('Configuration file is missing.');
}

$env = parse_ini_file($envFile);

$groqApiKey = $env['GROQ_API_KEY'] ?? '';

if (!$groqApiKey) {
    die('GROQ_API_KEY is not configured.');
}

$groqApiUrl = 'https://api.groq.com/openai/v1/chat/completions';
$groqModel = 'openai/gpt-oss-20b';
