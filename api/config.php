<?php
// Load environment variables from .env file
if (file_exists(__DIR__ . '/../.env')) {
    $enviromentvar = parse_ini_file(__DIR__ . '/../.env');
    foreach ($enviromentvar as $key => $value) {
        putenv("$key=$value");
    }
}

// API-Football API Key (only if it didnt load the API key from the .env file)
define('API_KEY', getenv('API_KEY') ?: 'your_api_key_here');

// API Base URL
define('API_BASE_URL', 'https://v3.football.api-sports.io/');
?>