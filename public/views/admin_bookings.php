<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Rezerwacje</title>
    <link rel="stylesheet" type="text/css" href="/public/styles/main.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header admin-header">
        <div class="nav-greeting">
            ADMIN PANEL <span class="admin-badge">MASTER MODE</span>
        </div>
        <nav class="main-nav">
             <ul>
                 <li><a href="/admin_users" class="nav-link">Users</a></li>
                 <li><a href="/admin_rooms" class="nav-link">Rooms</a></li>
                 <li><a href="/admin_bookings" class="nav-link" style="font-weight:bold;">Bookings</a></li>
                 <li><a href="/myprofile" class="nav-link">My Profile</a></li>
                 <li><a href="/logout" class="nav-link">Log out</a></li>
             </ul>
        </nav>
    </header>

    <main style="padding: 3rem; max-width: 1200px; margin: 0 auto;">
        <h2 class="page-title">Manage All Bookings</h2>
        
        <div class="admin-table-container">
            <input type="text" id="searchInput" class="search-bar" placeholder="Szukaj rezerwacji (pokój, użytkownik, data)...">
            
            <table style="width: 100%; border-collapse: collapse;" id="adminTable">
                <thead>
                    <tr style="border-bottom: 2px solid #eee;">
                        <th style="text-align: left; padding: 10px;">ID</th>
                        <th style="text-align: left; padding: 10px;">Room & User</th>
                        <th style="text-align: left; padding: 10px;">Date</th>
                        <th style="text-align: left; padding: 10px;">Time</th>
                        <th style="text-align: left; padding: 10px;">Status</th>
                        <th style="text-align: center; padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $booking): ?>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 10px; font-weight: bold; color: #666;">
                                #<?= $booking->getId() ?>
                            </td>
                            <td style="padding: 10px;">
                                <?= htmlspecialchars($booking->getRoomName()) ?>
                            </td>
                            <td style="padding: 10px;">
                                <?= htmlspecialchars($booking->getDate()) ?>
                            </td>
                            <td style="padding: 10px;">
                                <?= htmlspecialchars($booking->getTimeRange()) ?>
                            </td>
                            <td style="padding: 10px;">
                                <?php 
                                    $status = $booking->getStatus();
                                    $pillClass = 'pill-gray';
                                    if ($status === 'Confirmed') $pillClass = 'pill-green';
                                    if ($status === 'Cancelled') $pillClass = 'pill-red';
                                    if ($status === 'Pending') $pillClass = 'pill-orange';
                                ?>
                                <span class="pill <?= $pillClass ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td style="padding: 10px; text-align: center;">
                                <form action="/admin_delete_booking" method="POST" onsubmit="return confirm('Czy na pewno usunąć tę rezerwację?');">
                                    <input type="hidden" name="id" value="<?= $booking->getId() ?>">
                                    <button type="submit" style="color: #e53e3e; background: none; border: none; cursor: pointer; transition: transform 0.2s;" title="Delete Booking">
                                        <span class="material-icons-outlined">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #888;">
                                Brak rezerwacji w systemie.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        // Proste wyszukiwanie po tabeli
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#adminTable tbody tr');
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>