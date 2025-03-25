<?php
require_once __DIR__ . '/../api/football_api.php';

$competition = $_GET['competition'] ?? '';
$season = $_GET['season'] ?? '';

header('Content-Type: application/json');

if ($competition && $season) {
    $fixtures = getFixturesByCompetition($competition, (int)$season);

    if (isset($fixtures['error'])) {
        echo json_encode(['error' => $fixtures['error']]);
    } else {
        echo json_encode(['fixtures' => $fixtures]);
    }
} else {
    echo json_encode(['error' => 'Invalid parameters']);
}
?>
