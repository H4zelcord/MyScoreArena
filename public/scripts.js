console.log('scripts.js is loaded'); // Debugging: Confirm the script is loaded

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM fully loaded and parsed'); // Debugging: Confirm DOM is ready

    const fetchDataButton = document.getElementById('fetchData');
    if (fetchDataButton) {
        console.log('Fetch Data button found'); // Debugging: Confirm button exists
        fetchDataButton.addEventListener('click', () => {
            console.log('Fetch Data button clicked'); // Debugging: Confirm button click

            const league = document.getElementById('league').value;
            const season = document.getElementById('season').value;

            // Check if the selected season is supported
            if (parseInt(season) < 2021 || parseInt(season) > 2023) {
                const leagueDataDiv = document.getElementById('leagueData');
                leagueDataDiv.innerHTML = `<p class="text-danger">The selected season is not supported. Please choose a season between 2021 and 2023.</p>`;
                return;
            }

            fetch(`fetch_data.php?league=${encodeURIComponent(league)}&season=${encodeURIComponent(season)}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Response:', data); // Debugging: Log the response

                    const leagueDataDiv = document.getElementById('leagueData');
                    leagueDataDiv.innerHTML = '<h2>League Data</h2>';

                    if (data.error) {
                        leagueDataDiv.innerHTML += `<p>Error: ${data.error}</p>`;
                    } else if (data.leagueData && data.leagueData.length > 0) {
                        const table = document.createElement('table');
                        table.innerHTML = `
                            <thead>
                                <tr>
                                    <th>Position</th>
                                    <th>Team</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.leagueData.map(team => `
                                    <tr>
                                        <td>${team.rank}</td>
                                        <td>${team.team.name}</td>
                                        <td>${team.points}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        `;
                        leagueDataDiv.appendChild(table);
                    } else {
                        leagueDataDiv.innerHTML += '<p>No league data found.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    document.getElementById('leagueData').innerHTML = `<p>Error fetching data: ${error.message}</p>`;
                });
        });
    } else {
        console.error('Fetch Data button not found in the DOM.');
    }

    const fetchFixturesButton = document.getElementById('fetchFixtures');
    if (fetchFixturesButton) {
        fetchFixturesButton.addEventListener('click', () => {
            console.log('Fetch Fixtures button clicked'); // Debugging: Confirm button click

            const competition = document.getElementById('competition').value;
            const season = document.getElementById('fixtureSeason').value;

            fetch(`fetch_fixtures.php?competition=${encodeURIComponent(competition)}&season=${encodeURIComponent(season)}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Fixtures Response:', data); // Debugging: Log the response

                    const fixturesDataDiv = document.getElementById('fixturesData');
                    fixturesDataDiv.innerHTML = '<h2>Fixtures Data</h2>';

                    if (data.error) {
                        fixturesDataDiv.innerHTML += `<p>Error: ${data.error}</p>`;
                    } else if (data.fixtures && data.fixtures.length > 0) {
                        const table = document.createElement('table');
                        table.innerHTML = `
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Home Team</th>
                                    <th>Away Team</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.fixtures.map(fixture => {
                                    const homeTeamName = fixture.teams?.home?.name || 'Unknown';
                                    const awayTeamName = fixture.teams?.away?.name || 'Unknown';
                                    const fixtureDate = fixture.fixture?.date || 'Unknown Date';

                                    return `
                                        <tr>
                                            <td>${new Date(fixtureDate).toLocaleString()}</td>
                                            <td>${homeTeamName}</td>
                                            <td>${awayTeamName}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info" onclick="fetchEvents(${fixture.fixture.id})">View Events</button>
                                            </td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        `;
                        fixturesDataDiv.appendChild(table);
                    } else {
                        fixturesDataDiv.innerHTML += '<p>No fixtures found.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching fixtures:', error);
                    document.getElementById('fixturesData').innerHTML = `<p>Error fetching fixtures: ${error.message}</p>`;
                });
        });
    }
});

function fetchEvents(fixtureId) {
    console.log('Fetching events for fixture ID:', fixtureId); // Debugging: Log the fixture ID

    fetch(`fetch_events.php?fixtureId=${fixtureId}`)
        .then(response => response.json())
        .then(data => {
            console.log('Events Response:', data); // Debugging: Log the response

            const eventsDataDiv = document.getElementById('eventsData');
            eventsDataDiv.innerHTML = '<h2>Events Data</h2>';

            if (data.error) {
                eventsDataDiv.innerHTML += `<p class="text-danger">Error: ${data.error}</p>`;
            } else if (data.events && data.events.length > 0) {
                const table = document.createElement('table');
                table.className = 'table table-striped table-bordered';
                table.innerHTML = `
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Detail</th>
                            <th>Player</th>
                            <th>Team</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.events.map(event => `
                            <tr>
                                <td>${event.time.elapsed || 'N/A'}'</td>
                                <td>${event.type || 'N/A'}</td>
                                <td>${event.detail || 'N/A'}</td>
                                <td>${event.player?.name || 'N/A'}</td>
                                <td>${event.team?.name || 'N/A'}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                `;
                eventsDataDiv.appendChild(table);
            } else {
                eventsDataDiv.innerHTML += '<p>No events found for this fixture.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching events:', error);
            document.getElementById('eventsData').innerHTML = `<p class="text-danger">Error fetching events: ${error.message}</p>`;
        });
}
