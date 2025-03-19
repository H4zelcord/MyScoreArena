<?php
// /api/football_api.php

require_once __DIR__ . '/config.php';
$leagueIds = require __DIR__ . '/league_ids.php'; // Import the league IDs array

/**
 * Make a request to the API-Football API.
 */
function makeApiRequest($endpoint, $params = []) {
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

    return json_decode($response, true);
}

/**
 * Retrieve fixtures for a specific league.
 */
function getLeagueFixtures($leagueName, $season) {
    global $leagueIds;

    $leagueId = $leagueIds[$leagueName] ?? null;

    if (!$leagueId) {
        return ['error' => 'League not found'];
    }

    $endpoint = 'fixtures';
    $params = ['league' => $leagueId, 'season' => $season];
    $response = makeApiRequest($endpoint, $params);

    return $response['response'] ?? [];
}

/**
 * Retrieve information about a specific club.
 */
function getClubInfo($clubId) {
    $endpoint = 'teams';
    $params = ['id' => $clubId];
    $response = makeApiRequest($endpoint, $params);

    return $response['response'][0] ?? [];
}

/**
 * Retrieve standings for a specific league.
 */
function getLeagueStandings($leagueName, $season) {
    global $leagueIds;

    $leagueId = $leagueIds[$leagueName] ?? null;

    if (!$leagueId) {
        return ['error' => 'League not found'];
    }

    $endpoint = 'standings';
    $params = ['league' => $leagueId, 'season' => $season];
    $response = makeApiRequest($endpoint, $params);

    return $response['response'][0]['league']['standings'][0] ?? [];
}

/**
 * Retrieve detailed information about a specific match.
 *
 * @param int $matchId The ID of the match.
 * @return array Match details including predictions, odds, and live stats (if available).
 */
function getMatchDetails($matchId) {
    // Get basic match details
    $endpoint = 'fixtures';
    $params = ['id' => $matchId];
    $matchResponse = makeApiRequest($endpoint, $params);

    if (empty($matchResponse['response'])) {
        return ['error' => 'Match not found'];
    }

    $matchDetails = $matchResponse['response'][0];

    // Get predictions (if available)
    $endpoint = 'predictions';
    $predictionResponse = makeApiRequest($endpoint, $params);
    $matchDetails['predictions'] = $predictionResponse['response'][0] ?? null;

    // Get odds (if available)
    $endpoint = 'odds';
    $oddsResponse = makeApiRequest($endpoint, $params);
    $matchDetails['odds'] = $oddsResponse['response'] ?? null;

    // Get live stats (if the match is live)
    if ($matchDetails['fixture']['status']['short'] === 'LIVE') {
        $endpoint = 'fixtures/statistics';
        $liveStatsResponse = makeApiRequest($endpoint, $params);
        $matchDetails['live_stats'] = $liveStatsResponse['response'] ?? null;
    } else {
        $matchDetails['live_stats'] = null;
    }

    return $matchDetails;
}
?>