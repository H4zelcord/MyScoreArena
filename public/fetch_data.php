<?php
require_once __DIR__ . '/../api/football_api.php';

$league = $_GET['league'] ?? '';
$season = $_GET['season'] ?? '';

header('Content-Type: application/json');

if ($league && $season) {
    $leagueData = getDataByLeague($league, (int)$season);

    // Debugging: Log the raw API response
    error_log('API Response for League: ' . $league . ', Season: ' . $season . ' => ' . print_r($leagueData, true));

    if (isset($leagueData['error'])) {
        echo json_encode(['error' => $leagueData['error']]);
    } else {
        echo json_encode(['leagueData' => $leagueData]);
    }
} else {
    echo json_encode(['error' => 'Invalid parameters']);
}
?>
