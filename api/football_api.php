<?php
// /api/football_api.php

require_once __DIR__ . '/config.php';
$leagueIds = require __DIR__ . '/league_ids.php'; // Import the league IDs array

/**
 * @brief Cache API responses to a file.
 * 
 * @param string $cacheKey The unique key for the cache file.
 * @param array $data The data to be cached.
 */
function cacheApiResponse($cacheKey, $data) {
    $cacheDir = __DIR__ . '/../data/cache/';
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }
    $cacheFile = $cacheDir . $cacheKey . '.json';
    file_put_contents($cacheFile, json_encode($data, JSON_PRETTY_PRINT));
}

/**
 * @brief Retrieve cached data if available.
 * 
 * @param string $cacheKey The unique key for the cache file.
 * @return array|null The cached data or null if not available.
 */
function getCachedResponse($cacheKey) {
    $cacheFile = __DIR__ . '/../data/cache/' . $cacheKey . '.json';
    if (file_exists($cacheFile)) {
        return json_decode(file_get_contents($cacheFile), true);
    }
    return null;
}

/**
 * @brief Make a request to the API-Football API and populate the cache.
 * 
 * @param string $endpoint The API endpoint to call.
 * @param array $params The query parameters for the API call.
 * @return array The API response data.
 */
function makeApiRequestWithCache($endpoint, $params = []) {
    $cacheKey = md5($endpoint . json_encode($params));
    $cachedData = getCachedResponse($cacheKey);

    if ($cachedData) {
        return $cachedData;
    }

    $url = API_BASE_URL . $endpoint . '?' . http_build_query($params);
    $headers = [
        'x-rapidapi-host: v3.football.api-sports.io',
        'x-rapidapi-key: ' . API_KEY
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    $decodedResponse = json_decode($response, true);

    // Ensure the cache is populated with non-duplicated data
    if (!empty($decodedResponse)) {
        cacheApiResponse($cacheKey, $decodedResponse);
    }

    return $decodedResponse;
}

/**
 * @brief Retrieve data by league name.
 * 
 * @param string $leagueName The name of the league.
 * @param int $season The season year.
 * @return array The league standings data.
 */
function getDataByLeague($leagueName, $season) {
    global $leagueIds;

    $leagueId = $leagueIds[$leagueName] ?? null;
    if (!$leagueId) {
        return ['error' => 'League not found'];
    }

    $endpoint = 'standings';
    $params = ['league' => $leagueId, 'season' => $season];

    error_log('API Request: Endpoint=' . $endpoint . ', Params=' . json_encode($params));

    $response = makeApiRequestWithCache($endpoint, $params);

    error_log('API Response: ' . print_r($response, true));

    if (empty($response['response'])) {
        return ['error' => 'No data available for the selected season'];
    }

    return $response['response'][0]['league']['standings'][0] ?? [];
}

/**
 * @brief Retrieve data by team ID.
 * 
 * @param int $teamId The ID of the team.
 * @return array The team information data.
 */
function getDataByTeam($teamId) {
    $endpoint = 'teams';
    $params = ['id' => $teamId];
    $response = makeApiRequestWithCache($endpoint, $params);

    return $response['response'][0] ?? [];
}

/**
 * @brief Retrieve fixtures for a specific Spanish competition and season.
 * 
 * @param string $competition The name of the competition (e.g., "La Liga", "Copa del Rey").
 * @param int $season The season year.
 * @return array The fixtures data.
 */
function getFixturesByCompetition($competition, $season) {
    global $leagueIds;

    $leagueId = $leagueIds[$competition] ?? null;
    if (!$leagueId) {
        return ['error' => 'Competition not found'];
    }

    $endpoint = 'fixtures';
    $params = ['league' => $leagueId, 'season' => $season];

    error_log('API Request: Endpoint=' . $endpoint . ', Params=' . json_encode($params));

    $response = makeApiRequestWithCache($endpoint, $params);

    error_log('API Response: ' . print_r($response, true));

    if (empty($response['response'])) {
        return ['error' => 'No fixtures available for the selected competition and season'];
    }

    return $response['response'];
}

/**
 * @brief Retrieve events for a specific fixture.
 * 
 * @param int $fixtureId The ID of the fixture.
 * @return array The events data.
 */
function getEventsByFixture($fixtureId) {
    $endpoint = 'fixtures/events';
    $params = ['fixture' => $fixtureId];

    error_log('API Request: Endpoint=' . $endpoint . ', Params=' . json_encode($params));

    $response = makeApiRequestWithCache($endpoint, $params);

    error_log('API Response: ' . print_r($response, true));

    if (empty($response['response'])) {
        return ['error' => 'No events available for the selected fixture'];
    }

    return $response['response'];
}
?>