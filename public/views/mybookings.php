<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - Moje Rezerwacje</title>
    <link rel="stylesheet" type="text/css" href="public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="public/styles/bookings.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    
    <header class="main-header">
        <nav class="main-nav">
             <ul>
                 <li><a href="/about" class="nav-link">About</a></li>
                 <li><a href="/mybookings" class="nav-link">My bookings</a></li>
                 <li><a href="/myprofile" class="nav-link">My profile</a></li>
                 <li><a href="/login" class="nav-link">Log out</a></li>
             </ul>
        </nav>
        <nav class="mobile-nav">
             <a href="/myprofile"><span class="material-icons-outlined">person_outline</span></a>
             <a href="/mybookings"><span class="material-icons-outlined">description</span></a>
             <a href="/login"><span class="material-icons-outlined">logout</span></a>
        </nav>
    </header>
    <main class="bookings-page-content">
        <div class="bookings-header">
            <h2 class="page-title">My bookings</h2>
            <a href="/reservation" class="fab desktop-fab">
                <span class="material-icons-outlined">add</span>
            </a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" aria-label="Zaznacz wszystko"></th>
                        <th>Room</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="actions-header">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Select"><input type="checkbox"></td>
                        <td data-label="Room">Aula A1 (AULA1)</td>
                        <td data-label="Date">Oct 25, 2025</td>
                        <td data-label="Time">10:00 - 12:00</td>
                        <td data-label="Type"><span class="pill pill-blue">Lecture Hall</span></td>
                        <td data-label="Status"><span class="pill pill-green">Confirmed</span></td>
                        <td data-label="Actions" class="actions-cell">
                            <button class="kebab-menu" aria-label="Opcje">
                                <span class="material-icons-outlined">more_vert</span>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td data-label="Select"><input type="checkbox"></td>
                        <td data-label="Room">Pokój Cichej Nauki (STUDYROOM1)</td>
                        <td data-label="Date">Oct 26, 2025</td>
                        <td data-label="Time">14:00 - 15:00</td>
                        <td data-label="Type"><span class="pill pill-gray">Study Room</span></td>
                        <td data-label="Status"><span class="pill pill-orange">Pending</span></td>
                        <td data-label="Actions" class="actions-cell">
                            <button class="kebab-menu" aria-label="Opcje">
                                <span class="material-icons-outlined">more_vert</span>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td data-label="Select"><input type="checkbox"></td>
                        <td data-label="Room">Pokój ROOM4</td>
                        <td data-label="Date">Oct 28, 2025</td>
                        <td data-label="Time">09:00 - 11:00</td>
                        <td data-label="Type"><span class="pill pill-gray">Lab</span></td>
                        <td data-label="Status"><span class="pill pill-red">Cancelled</span></td>
                        <td data-label="Actions" class="actions-cell">
                            <button class="kebab-menu" aria-label="Opcje">
                                <span class="material-icons-outlined">more_vert</span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a href="/reservation" class="fab mobile-fab">
            <span class="material-icons-outlined">add</span>
        </a>
    </main>
</body>
</html>