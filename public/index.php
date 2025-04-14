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
    <title>MyScoreArena</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Ensure no unintended overflow */
        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }

        body.bg-light {
            background-color:#A3CCFF !important; /* Blue color similar to sea tiles */
            overflow-y: auto; /* Allow vertical scrolling when content overflows */
        }

        #mainMenu {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Ensure it adjusts with content height */
            position: relative;
            text-align: center;
        }

        #mainMenu .card {
            background-color: white; /* Fully opaque background */
            opacity: 1; /* Ensure no transparency */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative; /* Ensure the card is positioned relative to the parent */
            z-index: 1; /* Higher z-index to appear above the image */
        }

        #mainMenu img {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Center the image */
            height: 100%; /* Ensure the image spans the full height of the page */
            width: auto; /* Maintain aspect ratio */
            opacity: 0.8;
            pointer-events: none; /* Allow clicks to pass through the image */
            z-index: 0; /* Lower z-index to place below the full-page content */
        }

        .side-box {
            position: fixed; /* Ensure the side boxes are fixed */
            top: 0;
            bottom: 0;
            width: 250px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 0; /* Lower z-index to place below the full-page content */
            overflow: hidden; /* Hide overflowing parts of the images */
            background-color: #A3CCFF !important;

        }

        .side-box img {
            position: absolute; /* Align the image within the box */
            top: 50%; /* Center vertically */
            left: 50%; /* Center horizontally */
            transform: translate(-50%, -50%); /* Adjust for the center point */
            height: calc(100vh - 10vh); /* Full height minus 5vh offset from top and bottom */
            width: auto; /* Maintain aspect ratio */
        }

        .side-box.left {
            left: 0;
        }

        .side-box.right {
            right: 0;
        }

        #fullPage {
        }

        #fullPage img {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Center the image */
            height: 100%; /* Ensure the image spans the full height of the page */
            width: auto; /* Maintain aspect ratio */
            opacity: 0.7; /* Slight transparency */
            pointer-events: none; /* Allow clicks to pass through the image */
            z-index: 0; /* Place below the content */
        }

        #fullPage .container {
        }

        #fullPage .card {
        }

        #fullPage h1, 
        #fullPage h2 {
            z-index: 4;
            text-shadow: 4px 4px 4px rgb(0, 0, 0); /* Subtle shadow for better readability */
            color: rgb(255, 255, 255); /* Ensure the text color contrasts with the background */
        }
    </style>
    <script>
        function showFrontend() {
            document.getElementById('mainMenu').style.display = 'none';
            document.getElementById('fullPage').style.display = 'block';
            document.body.style.setProperty('background-color', '#010605', 'important'); // Change background color with !important
            document.querySelectorAll('.side-box').forEach(box => box.style.display = 'none'); // Hide side boxes
        }
    </script>
    <script src="./scripts.js?v=1" defer></script>
</head>
<body class="bg-light">
    <noscript>
        <p class="text-danger text-center">JavaScript is disabled. Please enable JavaScript to use this feature.</p>
    </noscript>

    <!-- Side Boxes -->
    <div class="side-box left">
        <img src="../data/raphinha_celebrating.jpg" alt="Raphinha">
    </div>
    <div class="side-box right">
        <img src="../data/mbappe_backside.jpeg" alt="Mbappe">
    </div>

    <!-- Main Menu -->
    <div id="mainMenu">
        <div class="card">
            <h1 class="mb-4">Welcome to MyScoreArena</h1>
            <p class="mb-4">Your one-stop platform for football data and statistics.</p>
            <button class="btn btn-primary btn-lg" onclick="showFrontend()">Enter</button>
        </div>
        <img src="../data/spain_laliga_teams.png" alt="La Liga Teams">
    </div>

    <!-- Full Page Content -->
    <div id="fullPage" style="display: none;">
        <img src="../data/vinicius_foul.webp" alt="Vinicius Foul">
        <div class="container py-5">
            <h1 class="text-center mb-4">MyScoreArena: Main Page</h1>

            <div class="card p-4 mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="league" class="form-label">Select League:</label>
                        <select id="league" class="form-select">
                            <option value="Premier League">Premier League</option>
                            <option value="Bundesliga">Bundesliga</option>
                            <option value="Serie A">Serie A</option>
                            <option value="Ligue 1">Ligue 1</option>
                            <option value="La Liga">La Liga</option>
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
                            <option value="Copa del Rey">Copa del Rey</option>
                            <option value="La Liga">La Liga</option>
                            <option value="La Liga 2">La Liga 2</option>
                            <option value="La Liga F">La Liga F</option>
                            <option value="Primera Division RFEF Group 1">Primera Division RFEF Group 1</option>
                            <option value="Primera Division RFEF Group 2">Primera Division RFEF Group 2</option>
                            <option value="Primera Division RFEF Playoffs">Primera Division RFEF Playoffs</option>
                            <option value="Segunda Division RFEF Group 1">Segunda Division RFEF Group 1</option>
                            <option value="Segunda Division RFEF Group 2">Segunda Division RFEF Group 2</option>
                            <option value="Segunda Division RFEF Group 3">Segunda Division RFEF Group 3</option>
                            <option value="Segunda Division RFEF Group 4">Segunda Division RFEF Group 4</option>
                            <option value="Segunda Division RFEF Group 5">Segunda Division RFEF Group 5</option>
                            <option value="Segunda Division RFEF Playoffs">Segunda Division RFEF Playoffs</option>
                            <option value="Tercera Division RFEF Group 1">Tercera Division RFEF Group 1</option>
                            <option value="Tercera Division RFEF Group 2">Tercera Division RFEF Group 2</option>
                            <option value="Tercera Division RFEF Group 3">Tercera Division RFEF Group 3</option>
                            <option value="Tercera Division RFEF Group 4">Tercera Division RFEF Group 4</option>
                            <option value="Tercera Division RFEF Group 5">Tercera Division RFEF Group 5</option>
                            <option value="Tercera Division RFEF Group 6">Tercera Division RFEF Group 6</option>
                            <option value="Tercera Division RFEF Group 7">Tercera Division RFEF Group 7</option>
                            <option value="Tercera Division RFEF Group 8">Tercera Division RFEF Group 8</option>
                            <option value="Tercera Division RFEF Group 9">Tercera Division RFEF Group 9</option>
                            <option value="Tercera Division RFEF Group 10">Tercera Division RFEF Group 10</option>
                            <option value="Tercera Division RFEF Group 11">Tercera Division RFEF Group 11</option>
                            <option value="Tercera Division RFEF Group 12">Tercera Division RFEF Group 12</option>
                            <option value="Tercera Division RFEF Group 13">Tercera Division RFEF Group 13</option>
                            <option value="Tercera Division RFEF Group 14">Tercera Division RFEF Group 14</option>
                            <option value="Tercera Division RFEF Group 15">Tercera Division RFEF Group 15</option>
                            <option value="Tercera Division RFEF Group 16">Tercera Division RFEF Group 16</option>
                            <option value="Tercera Division RFEF Group 17">Tercera Division RFEF Group 17</option>
                            <option value="Tercera Division RFEF Group 18">Tercera Division RFEF Group 18</option>
                            <option value="Tercera Division RFEF Playoffs">Tercera Division RFEF Playoffs</option>
                            <!-- Add more competitions as needed -->
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
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>