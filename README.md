# MyScoreArena Project

This project interacts with the **API-Football API** (provided by [API-Sports](https://api-sports.io/)) to retrieve and display football-related data such as fixtures, standings, team information, and match details. The project is built using PHP and is designed to work within the limitations of the API's free plan.

---

## Features

1. **Retrieve League Fixtures**:
   - Fetch fixtures for a specific league and season.
   - Display match details such as teams, date, and time.

2. **Retrieve Team Information**:
   - Get details about a specific football club, including name, country, founding year, and logo.

3. **Retrieve League Standings**:
   - Fetch and display the current standings for a specific league and season.

4. **Retrieve Match Details**:
   - Get detailed information about a specific match, including:
     - Predictions
     - Odds
     - ~Live stats (if the match is live)~ (this cannot be done without premium plan from the API key website)

5. **Caching and Rate Limit Handling**:
   - Cache API responses to reduce the number of API calls. (WIP)
   - Handle API rate limits gracefully (10 requests per minute, 100 requests per day).

---

## New Features

1. **Caching**:
   - API responses are cached in the `/data/cache` directory to reduce API calls and improve performance.
   - Cached data expires after 1 hour (configurable).

2. **Simplified Data Retrieval**:
   - Use `getDataByLeague($leagueName, $season)` to retrieve league standings.
   - Use `getDataByTeam($teamId)` to retrieve team information.

---

## Project Structure
```
MYSCOREARENA/
├── api/
│   ├── config.php
│   ├── football_api.php
│   └── league_ids.php
├── data/
│   └── cache/
├── logs/
│   └── error.log
├── public/
│   ├── fetch_data.php
│   ├── fetch_events.php
│   ├── fetch_fixtures.php
│   ├── index.php
│   ├── scripts.js
│   └── style.css
└── README.md
```

---

## Setup Instructions

1. **Get an API Key**:
   - Sign up at [API-Football](https://www.api-football.com/) to get your API key.

2. **Update Configuration**:
   - Open `/api/config.php` and replace `'your_api_key_here'` with your actual API key.

3. **Run the Project**:
   - Place the project files on a PHP-enabled server.
   - Access the `index.php` file in your browser (e.g., `http://localhost/MyScoreNET/public/index.php`).

---

## License
This project is open-source and available under the MIT License.

---

## Contact
For questions or support, please open an issue on the project repository, or maybe contact me through my socials or mail (h4zelcord04@gmail.com).

- [Instagram](https://www.instagram.com/h4zelcord/)
- [Twitter / X](https://x.com/H4zelcord)


---
