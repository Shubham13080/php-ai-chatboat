
<?php

// 1. First, get the API key from Vercel Environment Variables
$groqApiKey = getenv('GROQ_API_KEY') ?: '';

// 2. If running locally, read the .env file
if ($groqApiKey === '') {
    $envFile = __DIR__ . '/.env';

    if (file_exists($envFile)) {
        $env = parse_ini_file($envFile, false, INI_SCANNER_RAW);

        if (is_array($env)) {
            $groqApiKey = $env['GROQ_API_KEY'] ?? '';
        }
    }
}

// 3. Groq API configuration
$groqApiUrl = 'https://api.groq.com/openai/v1/chat/completions';
$groqModel = 'openai/gpt-oss-20b';
