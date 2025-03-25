<?php
require_once __DIR__ . '/../api/football_api.php';

$fixtureId = $_GET['fixtureId'] ?? '';

header('Content-Type: application/json');

if ($fixtureId) {
    $events = getEventsByFixture((int)$fixtureId);

    if (isset($events['error'])) {
        echo json_encode(['error' => $events['error']]);
    } else {
        echo json_encode(['events' => $events]);
    }
} else {
    echo json_encode(['error' => 'Invalid parameters']);
}
?>
