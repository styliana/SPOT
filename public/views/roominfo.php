<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - <?php echo htmlspecialchars($room['name']); ?></title>
    
    <link rel="stylesheet" type="text/css" href="/public/styles/main.css">
        <?php include __DIR__ . '/components/global_head_links.html'; ?>
        
    <style>

        .room-info-page-content {
            display: flex;
            justify-content: center;
            padding-top: 4rem;
            padding-bottom: 2rem;
            min-height: 100vh;
        }
        .room-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 600px;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            position: relative;
            text-align: center;
            margin-top: 1rem;
        }
        .back-link-wrapper {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
        }
        .back-button {
            text-decoration: none;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            transition: 0.2s;
        }
        .back-button:hover {
            background-color: #f3f4f6;
            color: #0A6BEF;
        }
        .room-header {
            margin-bottom: 2rem;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1.5rem;
        }
        .room-icon-circle {
            display: inline-block;
            background-color: #E0EEFF;
            color: #0A6BEF;
            padding: 1rem;
            border-radius: 50%;
            margin-bottom: 1rem;
        }
        .room-name {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0.5rem 0;
        }
        .room-details {
            text-align: left;
            margin-bottom: 2.5rem;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px dashed #e5e7eb;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .detail-value {
            font-weight: 500;
            color: #1f2937;
            font-size: 1rem;
        }
        .choose-room-btn {
            display: block;
            width: 100%;
            background-color: #0A6BEF;
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 1rem;
            border-radius: 50px;
            transition: background 0.2s;
            box-shadow: 0 4px 15px rgba(10, 107, 239, 0.3);
        }
        .choose-room-btn:hover {
            background-color: #0855BB;
            transform: translateY(-2px);
        
    </style>
</head>
<body>
    
    <?php include __DIR__ . '/components/header.php'; ?>

    <main class="room-info-page-content">
        
        <div class="room-card">
            
            <?php
                // === LOGIKA ZACHOWANIA DANYCH I WŁAŚCICIELA ===
                $date = $_GET['date'] ?? '';
                $start = $_GET['start'] ?? '';
                $end = $_GET['end'] ?? '';
                $bookingId = $_GET['booking_id'] ?? '';
                $ownerId = $_GET['owner_id'] ?? ''; // <-- WAŻNE: Przekazujemy to dalej
                
                // Budujemy parametry URL
                $params = "date=$date&start=$start&end=$end&booking_id=$bookingId&owner_id=$ownerId";

                $backLink = "/reservation?" . $params;
                $chooseLink = "/reservation?room_id=" . htmlspecialchars($room['id']) . "&" . $params;
            ?>

            <div class="back-link-wrapper">
                <a href="<?php echo $backLink; ?>" class="back-button">
                    <span class="material-icons-outlined">arrow_back</span>
                </a>
            </div>

            <div class="room-header">
                <div class="room-icon-circle">
                    <span class="material-icons-outlined" style="font-size: 40px;">meeting_room</span>
                </div>
                <h2 class="room-name"><?php echo htmlspecialchars($room['name']); ?></h2>
                <span class="pill pill-blue"><?php echo htmlspecialchars($room['type']); ?></span>
            </div>

            <div class="room-details">
                <div class="detail-item">
                    <span class="detail-label">Workspaces</span>
                    <span class="detail-value"><?php echo htmlspecialchars($room['workspaces']); ?> miejsc</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Description</span>
                    <span class="detail-value"><?php echo htmlspecialchars($room['description']); ?></span>
                </div>
            </div>

            <a href="<?php echo $chooseLink; ?>" class="choose-room-btn">
                Choose this room
            </a>

        </div>
    </main>

</body>
</html>