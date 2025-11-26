<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Rezerwacje</title>

    <?php include __DIR__ . '/components/global_head_links.php'; ?>

    <style>
        /* === STYLE ADMINA (WBUDOWANE) === */
        
        body { background-color: #f3f4f6 !important; }

        /* Header */
        .admin-header {
            background-color: #1f2937 !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .admin-badge {
            background: #ef4444; color: white; padding: 3px 8px; border-radius: 6px;
            font-size: 0.7rem; margin-left: 8px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;
        }
        .nav-link.active {
            color: #60a5fa !important; font-weight: 700; position: relative;
        }
        .nav-link.active::after {
            content: ''; position: absolute; bottom: -22px; left: 0; width: 100%; height: 3px; background-color: #60a5fa;
        }

        /* Kontener */
        .admin-container {
            max-width: 1300px; margin: 3rem auto; padding: 0 2rem; padding-bottom: 100px;
        }

        /* Panel Górny */
        .admin-header-panel {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;
        }
        .page-title {
            font-size: 2rem; color: #1f2937; font-weight: 800; margin: 0;
        }
        .sort-hint {
            font-size: 0.85rem; color: #6b7280; margin-top: 5px; display: flex; align-items: center; gap: 5px;
        }

        /* Wyszukiwarka */
        .search-wrapper { position: relative; width: 320px; }
        .search-wrapper input {
            width: 100%; padding: 12px 15px 12px 45px; border-radius: 50px; border: 1px solid #e5e7eb;
            background-color: white; font-family: 'Poppins', sans-serif; font-size: 0.9rem;
            outline: none; box-shadow: 0 2px 8px rgba(0,0,0,0.03); transition: all 0.3s ease;
        }
        .search-wrapper input:focus {
            border-color: #0A6BEF; box-shadow: 0 4px 15px rgba(10, 107, 239, 0.15); width: 340px;
        }
        .search-wrapper .search-icon {
            position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 22px;
        }

        /* Tabela Desktop */
        .table-container {
            background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden; border: 1px solid rgba(0,0,0,0.02);
        }
        table { width: 100%; border-collapse: collapse; }
        th {
            background-color: #fff; padding: 1.5rem 1.5rem; text-align: left; font-weight: 700;
            color: #6b7280; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.8px;
            border-bottom: 2px solid #f3f4f6; cursor: pointer; transition: 0.2s;
        }
        th:hover { background-color: #f9fafb; color: #0A6BEF; }
        td {
            padding: 1.2rem 1.5rem; border-bottom: 1px solid #f3f4f6;
            color: #374151; font-size: 0.95rem; vertical-align: middle;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #f8fafc; }

        /* Ikony Akcji */
        .actions-cell { display: flex; gap: 10px; justify-content: center; align-items: center; }
        .icon-btn {
            border: none; background: #f3f4f6; width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            transition: all 0.2s; color: #6b7280; text-decoration: none;
        }
        .icon-btn:hover { transform: translateY(-2px); color: white; }
        .icon-btn.edit:hover { background-color: #0A6BEF; box-shadow: 0 4px 10px rgba(10, 107, 239, 0.3); }
        .icon-btn.delete:hover { background-color: #ef4444; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3); }
        .icon-btn span { font-size: 20px; }

        /* Pigułki */
        .pill { padding: 5px 12px; border-radius: 15px; font-size: 0.85rem; font-weight: 600; display: inline-block; }
        .pill-blue { background-color: #E0EEFF; color: #0A6BEF; }
        .pill-green { background-color: #D1FAE5; color: #067647; }
        .pill-orange { background-color: #FFEDD5; color: #b45309; }
        .pill-red { background-color: #fee2e2; color: #b91c1c; }
        .pill-gray { background-color: #E5E7EB; color: #4b5563; }

        /* Mobile View */
        @media (max-width: 850px) {
            .admin-container { padding: 1rem; margin: 1rem auto; }
            .main-nav { display: none !important; }
            
            .admin-header-panel { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
            .search-wrapper, .search-wrapper input:focus { width: 100%; }

            .table-container { background: transparent; box-shadow: none; border: none; overflow: visible; }
            thead { display: none; }
            
            tr {
                display: flex; flex-direction: column; background: white; margin-bottom: 1.5rem;
                border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 1.5rem; border: 1px solid #eee;
            }
            td {
                display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 0;
                border-bottom: 1px dashed #f0f0f0; text-align: right; font-size: 0.95rem;
            }
            td:last-child { border-bottom: none; padding-top: 1.5rem; justify-content: space-between; gap: 1rem; }
            td::before {
                content: attr(data-label); font-weight: 700; font-size: 0.75rem; color: #9ca3af;
                text-transform: uppercase; letter-spacing: 1px;
            }
            
            .actions-cell { width: 100%; }
            .actions-cell .icon-btn { width: 48%; height: 45px; border-radius: 12px; font-size: 1.2rem; }
            .icon-btn.edit { background-color: #eff6ff; color: #0A6BEF; }
            .icon-btn.delete { background-color: #fef2f2; color: #ef4444; }

            .mobile-nav {
                display: flex !important; position: fixed; bottom: 0; left: 0; width: 100%;
                background-color: #1f2937; padding: 10px 15px; justify-content: space-between;
                align-items: center; box-shadow: 0 -5px 20px rgba(0,0,0,0.1); z-index: 9999;
                border-top-left-radius: 20px; border-top-right-radius: 20px;
            }
            .mobile-nav a {
                color: #9ca3af; text-decoration: none; display: flex; flex-direction: column;
                align-items: center; font-size: 0.7rem; gap: 4px; transition: color 0.2s; width: 60px;
            }
            .mobile-nav a.active { color: #60a5fa; }
            .mobile-nav a:hover { color: white; }
            .mobile-nav .material-icons-outlined { font-size: 26px; }
        }
    </style>
</head>
<body>
    
<?php include __DIR__ . '/components/header.php'; ?>

    <div class="admin-container">
        <div class="admin-header-panel">
            <div>
                <h2 class="page-title" style="margin:0;">Manage All Bookings</h2>
                <div class="sort-hint">
                    <span class="material-icons-outlined" style="font-size:16px;">info</span>
                    Tip: Click column headers to sort data
                </div>
            </div>
            
            <div class="search-wrapper">
                <span class="material-icons-outlined search-icon">search</span>
                <input type="text" id="searchInput" placeholder="Search bookings (room, user)...">
            </div>
        </div>
        
        <div class="table-container">
            <table id="bookingsTable">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">ID <span class="sort-arrow"></span></th>
                        <th onclick="sortTable(1)">Room & User <span class="sort-arrow"></span></th>
                        <th onclick="sortTable(2)">Date <span class="sort-arrow"></span></th>
                        <th onclick="sortTable(3)">Time <span class="sort-arrow"></span></th>
                        <th onclick="sortTable(4)">Type <span class="sort-arrow"></span></th>
                        <th onclick="sortTable(5)">Status <span class="sort-arrow"></span></th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $booking): ?>
                        <?php 
                            $times = explode(' - ', $booking->getTimeRange());
                            $start = $times[0] ?? '';
                            $end = $times[1] ?? '';
                            $rawRoomId = $booking->getRoomId(); 
                            
                            $editUrl = "/reservation?booking_id=" . $booking->getId() . 
                                       "&room_id=" . $rawRoomId .
                                       "&date=" . $booking->getDate() . 
                                       "&start=" . $start . 
                                       "&end=" . $end;

                            $status = $booking->getStatus();
                            $pillClass = 'pill-gray';
                            if ($status === 'Confirmed') $pillClass = 'pill-green';
                            if ($status === 'Cancelled') $pillClass = 'pill-red';
                            if ($status === 'Pending') $pillClass = 'pill-orange';
                        ?>
                        <tr>
                            <td data-label="ID" style="font-weight:bold; color:#9ca3af;">#<?= $booking->getId() ?></td>
                            <td data-label="Room & User" style="font-weight:500;"><?= htmlspecialchars($booking->getRoomName()) ?></td>
                            <td data-label="Date"><?= htmlspecialchars($booking->getDate()) ?></td>
                            <td data-label="Time"><?= htmlspecialchars($booking->getTimeRange()) ?></td>
                            <td data-label="Type"><span class="pill pill-blue"><?= htmlspecialchars($booking->getRoomType()) ?></span></td>
                            <td data-label="Status"><span class="pill <?= $pillClass ?>"><?= htmlspecialchars($status) ?></span></td>
                            
                            <td data-label="Actions" class="actions-cell">
                                <a href="<?= $editUrl ?>" class="icon-btn edit" title="Edit">
                                    <span class="material-icons-outlined">edit</span>
                                </a>

                                <form action="/admin_delete_booking" method="POST" style="display:inline;" onsubmit="return confirm('Delete this booking permanently?');">
                                    <input type="hidden" name="id" value="<?= $booking->getId() ?>">
                                    <button type="submit" class="icon-btn delete" title="Delete">
                                        <span class="material-icons-outlined">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 4rem; color: #9ca3af;">
                                No bookings found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <nav class="mobile-nav">
         <a href="/admin_users">
             <span class="material-icons-outlined">group</span>
             Users
         </a>
         <a href="/admin_rooms">
             <span class="material-icons-outlined">meeting_room</span>
             Rooms
         </a>
         <a href="/admin_bookings" class="active">
             <span class="material-icons-outlined">event_note</span>
             Bookings
         </a>
         <a href="/myprofile">
             <span class="material-icons-outlined">person</span>
             Profile
         </a>
         <a href="/logout">
             <span class="material-icons-outlined">logout</span>
             Exit
         </a>
    </nav>

    <script>
        // Wyszukiwanie
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#bookingsTable tbody tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Sortowanie
        function sortTable(n) {
            const table = document.getElementById("bookingsTable");
            let rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            switching = true;
            dir = "asc"; 
            
            document.querySelectorAll('.sort-arrow').forEach(el => el.textContent = '');
            const header = table.rows[0].getElementsByTagName("TH")[n];
            
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    let xVal = x.innerText.toLowerCase();
                    let yVal = y.innerText.toLowerCase();
                    if (n === 0) { xVal = parseInt(xVal.replace('#', '')); yVal = parseInt(yVal.replace('#', '')); }
                    if (dir == "asc") { if (xVal > yVal) { shouldSwitch = true; break; } } 
                    else if (dir == "desc") { if (xVal < yVal) { shouldSwitch = true; break; } }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount ++;
                } else {
                    if (switchcount == 0 && dir == "asc") { dir = "desc"; switching = true; }
                }
            }
            header.querySelector('.sort-arrow').textContent = dir === 'asc' ? ' ▲' : ' ▼';
        }
    </script>
</body>
</html>