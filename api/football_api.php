<?php
// /api/football_api.php

    require_once __DIR__ . '/config.php';
    $leagueIds = require __DIR__ . '/league_ids.php'; // Import the league IDs array

    /**
     * Cache API responses to a file.
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
     * Retrieve cached data if available.
     */
    function getCachedResponse($cacheKey) {
        $cacheFile = __DIR__ . '/../data/cache/' . $cacheKey . '.json';
        if (file_exists($cacheFile)) {
            return json_decode(file_get_contents($cacheFile), true);
        }
        return null;
    }

    /**
     * Make a request to the API-Football API and populate the cache.
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
     * Retrieve data by league name.
     */
    function getDataByLeague($leagueName, $season) {
        global $leagueIds;

        $leagueId = $leagueIds[$leagueName] ?? null;
        if (!$leagueId) {
            return ['error' => 'League not found'];
        }

        $endpoint = 'standings';
        $params = ['league' => $leagueId, 'season' => $season];
        $response = makeApiRequestWithCache($endpoint, $params);

        return $response['response'][0]['league']['standings'][0] ?? [];
    }

    /**
     * Retrieve data by team ID.
     */
    function getDataByTeam($teamId) {
        $endpoint = 'teams';
        $params = ['id' => $teamId];
        $response = makeApiRequestWithCache($endpoint, $params);

        return $response['response'][0] ?? [];
    }
?>