/**
 * @file scripts.js
 * @brief Handles client-side interactivity for fetching and displaying football data.
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM fully loaded and parsed');

    const fetchDataButton = document.getElementById('fetchData');
    const fetchFixturesButton = document.getElementById('fetchFixtures');

    if (fetchDataButton) {
        console.log('Fetch Data button found');
        fetchDataButton.addEventListener('click', () => {
            console.log('Fetch Data button clicked');
            const league = document.getElementById('league').value;
            const season = document.getElementById('season').value;

            if (!isValidSeason(season)) {
                console.warn('Invalid season selected:', season);
                displayError('leagueData', 'The selected season is not supported. Please choose a season between 2021 and 2023.');
                return;
            }

            console.log(`Fetching league data for league: ${league}, season: ${season}`);
            fetchAndRenderData(`fetch_data.php?league=${encodeURIComponent(league)}&season=${encodeURIComponent(season)}`, 'leagueData', renderLeagueData);
        });
    } else {
        console.error('Fetch Data button not found in the DOM.');
    }

    if (fetchFixturesButton) {
        console.log('Fetch Fixtures button found');
        fetchFixturesButton.addEventListener('click', () => {
            console.log('Fetch Fixtures button clicked');
            const competition = document.getElementById('competition').value;
            const season = document.getElementById('fixtureSeason').value;

            console.log(`Fetching fixtures for competition: ${competition}, season: ${season}`);
            fetchAndRenderData(`fetch_fixtures.php?competition=${encodeURIComponent(competition)}&season=${encodeURIComponent(season)}`, 'fixturesData', renderFixturesData);
        });
    } else {
        console.error('Fetch Fixtures button not found in the DOM.');
    }
});

/**
 * @brief Validates if the given season is within the supported range.
 * @param {string} season - The season year to validate.
 * @return {boolean} True if the season is valid, false otherwise.
 */
function isValidSeason(season) {
    const year = parseInt(season);
    const isValid = year >= 2021 && year <= 2023;
    console.log(`Season validation for ${season}: ${isValid}`);
    return isValid;
}

/**
 * @brief Displays an error message in the specified container.
 * @param {string} containerId - The ID of the container to display the error in.
 * @param {string} message - The error message to display.
 */
function displayError(containerId, message) {
    console.error(`Displaying error in container ${containerId}: ${message}`);
    const container = document.getElementById(containerId);
    container.innerHTML = `<p class="text-danger">${message}</p>`;
}

/**
 * @brief Fetches data from the given URL and renders it using the provided callback.
 * @param {string} url - The URL to fetch data from.
 * @param {string} containerId - The ID of the container to render the data in.
 * @param {function} renderCallback - The callback function to render the data.
 */
function fetchAndRenderData(url, containerId, renderCallback) {
    console.log(`Fetching data from URL: ${url}`);
    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log(`Response received for URL ${url}:`, data);
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            if (data.error) {
                console.warn(`Error in response for ${url}: ${data.error}`);
                displayError(containerId, data.error);
            } else {
                console.log(`Rendering data for container ${containerId}`);
                renderCallback(container, data);
            }
        })
        .catch(error => {
            console.error(`Error fetching data from ${url}:`, error);
            displayError(containerId, `Error fetching data: ${error.message}`);
        });
}

/**
 * @brief Renders league data in the specified container.
 * @param {HTMLElement} container - The container to render the data in.
 * @param {object} data - The league data to render.
 */
function renderLeagueData(container, data) {
    console.log('Rendering league data:', data);
    container.innerHTML = '<h2>League Data</h2>';
    if (data.leagueData && data.leagueData.length > 0) {
        const table = createTable(['Position', 'Team', 'Points'], data.leagueData.map(team => [
            team.rank,
            team.team.name,
            team.points
        ]));
        container.appendChild(table);
    } else {
        console.warn('No league data found');
        container.innerHTML += '<p>No league data found.</p>';
    }
}

/**
 * @brief Renders fixtures data in the specified container.
 * @param {HTMLElement} container - The container to render the data in.
 * @param {object} data - The fixtures data to render.
 */
function renderFixturesData(container, data) {
    console.log('Rendering fixtures data:', data);
    container.innerHTML = '<h2>Fixtures Data</h2>';
    if (data.fixtures && data.fixtures.length > 0) {
        const table = createTable(['Date', 'Home Team', 'Away Team', 'Actions'], data.fixtures.map(fixture => [
            new Date(fixture.fixture?.date || 'Unknown Date').toLocaleString(),
            fixture.teams?.home?.name || 'Unknown',
            fixture.teams?.away?.name || 'Unknown',
            `<button class="btn btn-sm btn-info" onclick="fetchEvents(${fixture.fixture.id})">View Events</button>`
        ]));
        container.appendChild(table);
    } else {
        console.warn('No fixtures data found');
        container.innerHTML += '<p>No fixtures found.</p>';
    }
}

/**
 * @brief Fetches and renders events for a specific fixture.
 * @param {number} fixtureId - The ID of the fixture to fetch events for.
 */
function fetchEvents(fixtureId) {
    console.log(`Fetching events for fixture ID: ${fixtureId}`);
    fetchAndRenderData(`fetch_events.php?fixtureId=${fixtureId}`, 'eventsData', renderEventsData);
}

/**
 * @brief Renders events data in the specified container.
 * @param {HTMLElement} container - The container to render the data in.
 * @param {object} data - The events data to render.
 */
function renderEventsData(container, data) {
    console.log('Rendering events data:', data);
    container.innerHTML = '<h2>Events Data</h2>';
    if (data.events && data.events.length > 0) {
        const table = createTable(['Time', 'Type', 'Detail', 'Player', 'Team'], data.events.map(event => [
            `${event.time.elapsed || 'N/A'}'`,
            event.type || 'N/A',
            event.detail || 'N/A',
            event.player?.name || 'N/A',
            event.team?.name || 'N/A'
        ]));
        container.appendChild(table);
    } else {
        console.warn('No events data found for this fixture');
        container.innerHTML += '<p>No events found for this fixture.</p>';
    }
}

/**
 * @brief Creates an HTML table with the given headers and rows.
 * @param {string[]} headers - The table headers.
 * @param {Array<Array<string>>} rows - The table rows.
 * @return {HTMLTableElement} The created table element.
 */
function createTable(headers, rows) {
    console.log('Creating table with headers:', headers);
    const table = document.createElement('table');
    table.className = 'table table-striped table-bordered';
    const thead = document.createElement('thead');
    const tbody = document.createElement('tbody');

    thead.innerHTML = `<tr>${headers.map(header => `<th>${header}</th>`).join('')}</tr>`;
    tbody.innerHTML = rows.map(row => `<tr>${row.map(cell => `<td>${cell}</td>`).join('')}</tr>`).join('');

    table.appendChild(thead);
    table.appendChild(tbody);
    return table;
}
