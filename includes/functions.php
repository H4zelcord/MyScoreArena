<?php
require_once __DIR__ . '/functions.php'; // Include the helper functions

/**
 * Retrieve fixtures for a specific league and season.
 *
 * @param int $leagueId The ID of the league (e.g., 39 for Premier League).
 * @param int $season The season year (e.g., 2023).
 * @return array|null Array of fixtures or null if no data found.
 */
function getFixturesByLeague($leagueId, $season) {
    $endpoint = 'fixtures';
    $params = [
        'league' => $leagueId,
        'season' => $season,
    ];
    $response = fetchData($endpoint, $params);
    return $response['response'] ?? null; // Return fixtures or null if no data
}

/**
 * Retrieve information about a specific club.
 *
 * @param int $clubId The ID of the club (e.g., 40 for Manchester United).
 * @return array|null Array of club information or null if no data found.
 */
function getClubInfo($clubId) {
    $endpoint = 'teams';
    $params = [
        'id' => $clubId,
    ];
    $response = fetchData($endpoint, $params);
    return $response['response'][0] ?? null; // Return club info or null if no data
}
?>