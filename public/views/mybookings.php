<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - Moje Rezerwacje</title>
    <link rel="stylesheet" type="text/css" href="/public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="/public/styles/bookings.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    
    <style>
        .actions-cell {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-start;
            align-items: center;
        }
        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-light);
            transition: color 0.2s;
            padding: 4px;
            display: flex;
            align-items: center;
            text-decoration: none; 
        }
        .icon-btn.delete:hover {
            color: #ef4444;
        }
        .icon-btn.edit:hover {
            color: var(--primary-blue);
        }
        .inline-form {
            display: inline;
            margin: 0;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/components/header.php'; ?>

    <main class="bookings-page-content">
        <div class="bookings-header">
            <h2 class="page-title">My bookings</h2>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
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
                            <?php 
                                $times = explode(' - ', $booking['time']);
                                $start = $times[0] ?? '';
                                $end = $times[1] ?? '';
                                
                                // === POPRAWKA: Używamy 'room_id' (przekazywanego przez kontroler) ===
                                $roomId = $booking['room_id'] ?? ''; 
                                
                                $editUrl = "/reservation?booking_id=" . $booking['id'] . 
                                           "&room_id=" . $roomId .
                                           "&date=" . $booking['date'] . 
                                           "&start=" . $start . 
                                           "&end=" . $end;
                            ?>
                            <tr>
                                <td data-label="Room"><?php echo htmlspecialchars($booking['room_name']); ?></td>
                                <td data-label="Date"><?php echo htmlspecialchars($booking['date']); ?></td>
                                <td data-label="Time"><?php echo htmlspecialchars($booking['time']); ?></td>
                                <td data-label="Type">
                                    <span class="pill <?php echo htmlspecialchars($booking['type_pill']); ?>">
                                        <?php echo htmlspecialchars($booking['type']); ?>
                                    </span>
                                </td>
                                <td data-label="Status">
                                    <span class="pill <?php echo htmlspecialchars($booking['status_pill']); ?>">
                                        <?php echo htmlspecialchars($booking['status']); ?>
                                    </span>
                                </td>
                                <td data-label="Actions" class="actions-cell">
                                    <a href="<?php echo $editUrl; ?>" class="icon-btn edit" title="Edit">
                                        <span class="material-icons-outlined">edit</span>
                                    </a>

                                    <form action="/delete_booking" method="POST" class="inline-form" onsubmit="return confirm('Are you sure?');">
                                        <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
                                        <button type="submit" class="icon-btn delete" title="Delete">
                                            <span class="material-icons-outlined">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-light);">
                                You have no bookings yet. <br>
                                <a href="/reservation" style="color: var(--primary-blue); font-weight: 600; text-decoration: none;">Book a room now!</a>
                            </td>
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