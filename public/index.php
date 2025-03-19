<?php
// /public/index.php

require_once __DIR__ . '/../api/football_api.php';

// Example: Get fixtures for Premier League 2023
$leagueName = 'Premier League';
$season = 2023;
$fixtures = getLeagueFixtures($leagueName, $season);

// Example: Get information about a specific club
$clubId = 40; // Liverpool ID
$clubInfo = getClubInfo($clubId);

// Example: Get standings for Premier League 2023
$standings = getLeagueStandings($leagueName, $season);

// Example: Get details for a specific match
$matchId = 12225; // Replace with a valid match ID
$matchDetails = getMatchDetails($matchId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Data</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Football Fixtures</h1>
    <?php if (!empty($fixtures) && !isset($fixtures['error'])): ?>
        <ul>
            <?php foreach ($fixtures as $fixture): ?>
                <li>
                    <?= htmlspecialchars($fixture['teams']['home']['name']) ?> vs <?= htmlspecialchars($fixture['teams']['away']['name']) ?>
                    <br>
                    <small>Date: <?= date('Y-m-d H:i', strtotime($fixture['fixture']['date'])) ?></small>
                    <br>
                    <a href="?match_id=<?= $fixture['fixture']['id'] ?>">View Details</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif (isset($fixtures['error'])): ?>
        <p><?= htmlspecialchars($fixtures['error']) ?></p>
    <?php else: ?>
        <p>No fixtures found.</p>
    <?php endif; ?>

    <h1>Club Information</h1>
    <?php if (!empty($clubInfo)): ?>
        <div>
            <h2><?= htmlspecialchars($clubInfo['team']['name']) ?></h2>
            <p>Country: <?= htmlspecialchars($clubInfo['team']['country']) ?></p>
            <p>Founded: <?= htmlspecialchars($clubInfo['team']['founded']) ?></p>
            <img src="<?= htmlspecialchars($clubInfo['team']['logo']) ?>" alt="<?= htmlspecialchars($clubInfo['team']['name']) ?> Logo">
        </div>
    <?php else: ?>
        <p>No club information found.</p>
    <?php endif; ?>

    <h1>League Standings</h1>
    <?php if (!empty($standings) && !isset($standings['error'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Team</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($standings as $team): ?>
                    <tr>
                        <td><?= htmlspecialchars($team['rank']) ?></td>
                        <td><?= htmlspecialchars($team['team']['name']) ?></td>
                        <td><?= htmlspecialchars($team['points']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (isset($standings['error'])): ?>
        <p><?= htmlspecialchars($standings['error']) ?></p>
    <?php else: ?>
        <p>No standings found.</p>
    <?php endif; ?>

    <h1>Match Details</h1>
    <?php if (!empty($matchDetails) && !isset($matchDetails['error'])): ?>
        <div>
            <h2><?= htmlspecialchars($matchDetails['teams']['home']['name']) ?> vs <?= htmlspecialchars($matchDetails['teams']['away']['name']) ?></h2>
            <p>Status: <?= htmlspecialchars($matchDetails['fixture']['status']['long']) ?></p>
            <p>Score: <?= htmlspecialchars($matchDetails['goals']['home']) ?> - <?= htmlspecialchars($matchDetails['goals']['away']) ?></p>

            <?php if (!empty($matchDetails['predictions'])): ?>
                <h3>Predictions</h3>
                <p>Winner: <?= htmlspecialchars($matchDetails['predictions']['predictions']['winner']['name']) ?></p>
                <p>Advice: <?= htmlspecialchars($matchDetails['predictions']['predictions']['advice']) ?></p>
            <?php endif; ?>

            <?php if (!empty($matchDetails['odds'])): ?>
                <h3>Odds</h3>
                <ul>
                    <?php foreach ($matchDetails['odds'] as $odd): ?>
                        <li>
                            <?= htmlspecialchars($odd['bookmaker']['name']) ?>:
                            <?= htmlspecialchars($odd['bets'][0]['values'][0]['odd']) ?> (<?= htmlspecialchars($odd['bets'][0]['values'][0]['value']) ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($matchDetails['live_stats'])): ?>
                <h3>Live Stats</h3>
                <ul>
                    <?php foreach ($matchDetails['live_stats'] as $stat): ?>
                        <li>
                            <?= htmlspecialchars($stat['type']) ?>:
                            <?= htmlspecialchars($stat['home']) ?> - <?= htmlspecialchars($stat['away']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php elseif (isset($matchDetails['error'])): ?>
        <p><?= htmlspecialchars($matchDetails['error']) ?></p>
    <?php else: ?>
        <p>No match details found.</p>
    <?php endif; ?>
</body>
</html>