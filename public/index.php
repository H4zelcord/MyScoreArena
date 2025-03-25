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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="./scripts.js?v=1" defer></script>
</head>
<body class="bg-light">
    <noscript>
        <p class="text-danger text-center">JavaScript is disabled. Please enable JavaScript to use this feature.</p>
    </noscript>
    <div class="container py-5">
        <h1 class="text-center mb-4">MyScoreArena: Main Page</h1>

        <div class="card p-4 mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="league" class="form-label">Select League:</label>
                    <select id="league" class="form-select">
                        <option value="Premier League">Premier League</option>
                        <option value="La Liga">La Liga</option>
                        <option value="Bundesliga">Bundesliga</option>
                        <!-- Add more leagues as needed -->
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="season" class="form-label">Select Season:</label>
                    <select id="season" class="form-select">
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                    </select>
                </div>
            </div>
            <div class="text-center mt-4">
                <button id="fetchData" class="btn btn-primary">Fetch Data</button>
            </div>
        </div>

        <div id="leagueData" class="card p-4 mb-4">
            <!-- League data will be dynamically loaded here -->
        </div>

        <div id="teamData" class="card p-4">
            <!-- Team data will be dynamically loaded here -->
        </div>
    </div>

    <div class="container py-5">
        <h2 class="text-center mb-4">Spanish Competitions: Fixtures and Events</h2>

        <div class="card p-4 mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="competition" class="form-label">Select Competition:</label>
                    <select id="competition" class="form-select">
                        <option value="La Liga">La Liga</option>
                        <option value="Copa del Rey">Copa del Rey</option>
                        <!-- Add more Spanish competitions as needed -->
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="fixtureSeason" class="form-label">Select Season:</label>
                    <select id="fixtureSeason" class="form-select">
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                    </select>
                </div>
            </div>
            <div class="text-center mt-4">
                <button id="fetchFixtures" class="btn btn-primary">Fetch Fixtures</button>
            </div>
        </div>

        <div id="eventsData" class="card p-4">
            <!-- Events data will be dynamically loaded here -->
        </div>
        <div id="fixturesData" class="card p-4 mb-4">
            <!-- Fixtures data will be dynamically loaded here -->
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>