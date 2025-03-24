<?php
// /public/index.php

require_once __DIR__ . '/../api/football_api.php';

// Example: Get data by league
$leagueName = 'Premier League';
$season = 2023;
$leagueData = getDataByLeague($leagueName, $season);

// Example: Get data by team
$teamId = 40; // Liverpool ID
$teamData = getDataByTeam($teamId);
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
    <h1>League Data</h1>
    <?php if (!empty($leagueData)): ?>
        <table>
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Team</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leagueData as $team): ?>
                    <tr>
                        <td><?= htmlspecialchars($team['rank']) ?></td>
                        <td><?= htmlspecialchars($team['team']['name']) ?></td>
                        <td><?= htmlspecialchars($team['points']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No league data found.</p>
    <?php endif; ?>

    <h1>Team Data</h1>
    <?php if (!empty($teamData)): ?>
        <div>
            <h2><?= htmlspecialchars($teamData['team']['name']) ?></h2>
            <p>Country: <?= htmlspecialchars($teamData['team']['country']) ?></p>
            <p>Founded: <?= htmlspecialchars($teamData['team']['founded']) ?></p>
            <img src="<?= htmlspecialchars($teamData['team']['logo']) ?>" alt="<?= htmlspecialchars($teamData['team']['name']) ?> Logo">
        </div>
    <?php else: ?>
        <p>No team data found.</p>
    <?php endif; ?>
</body>
</html>