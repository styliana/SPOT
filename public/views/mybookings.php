<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - Moje Rezerwacje</title>
    <link rel="stylesheet" type="text/css" href="/public/styles/main.css"> <link rel="stylesheet" type="text/css" href="/public/styles/bookings.css"> <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
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
                 <li><a href="/logout" class="nav-link">Log out</a></li>
             </ul>
        </nav>
        <nav class="mobile-nav">
             <a href="/myprofile"><span class="material-icons-outlined">person_outline</span></a>
             <a href="/mybookings"><span class="material-icons-outlined">description</span></a>
             <a href="/logout"><span class="material-icons-outlined">logout</span></a>
        </nav>
    </header>

    <main class="bookings-page-content">
        <div class="bookings-header">
            <h2 class="page-title">My bookings</h2>
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
                    <?php if (isset($bookings) && !empty($bookings)): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td data-label="Select"><input type="checkbox"></td>
                                <td data-label="Room"><?php echo htmlspecialchars($booking['room_name']); ?></td>
                                <td data-label="Date"><?php echo htmlspecialchars($booking['date']); ?></td>
                                <td data-label="Time"><?php echo htmlspecialchars($booking['time']); ?></td>
                                <td data-label="Type"><span class="pill <?php echo htmlspecialchars($booking['type_pill']); ?>"><?php echo htmlspecialchars($booking['type']); ?></span></td>
                                <td data-label="Status"><span class="pill <?php echo htmlspecialchars($booking['status_pill']); ?>"><?php echo htmlspecialchars($booking['status']); ?></span></td>
                                <td data-label="Actions" class="actions-cell">
                                    <button class="kebab-menu" aria-label="Opcje">
                                        <span class="material-icons-outlined">more_vert</span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem;">Nie masz jeszcze żadnych rezerwacji.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <a href="/reservation" class="fab-add-booking" aria-label="Add booking">
            <span class="material-icons-outlined">add</span>
        </a>
        </main>
</body>
</html>