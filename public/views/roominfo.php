<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - <?php echo htmlspecialchars($room['name']); ?></title>
    
    <link rel="stylesheet" type="text/css" href="/public/styles/main.css">
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <style>
        /* Tło strony */
        body {
            background-color: #f3f4f6 !important;
        }

        /* Główny kontener */
        .room-info-page-content {
            display: flex;
            justify-content: center;
            padding-top: 4rem;
            padding-bottom: 2rem;
            min-height: 100vh;
        }

        /* Biała Karta (Box) */
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

        /* Przycisk Wstecz */
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

        /* Nagłówek */
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

        /* Szczegóły */
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

        /* Przycisk Wyboru */
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
        }
        
        /* Pigułka */
        .pill {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        .pill-blue { background-color: #E0EEFF; color: #0A6BEF; }
    </style>
</head>
<body>
    
    <header class="main-header">
        <div class="nav-greeting">
            <?php if (isset($_SESSION['user_name'])): ?>
                Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
            <?php endif; ?>
        </div>
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

    <main class="room-info-page-content">
        
        <div class="room-card">
            
            <?php
                // === LOGIKA PRZEKAZYWANIA DANYCH ===
                // Pobieramy dane z URL (które przyszły z mapy)
                $date = $_GET['date'] ?? '';
                $start = $_GET['start'] ?? '';
                $end = $_GET['end'] ?? '';
                
                // Budujemy link powrotny (dla strzałki w tył)
                // Dzięki temu jak klikniesz Wstecz, dane w formularzu też zostaną!
                $backLink = "/reservation?date=$date&start=$start&end=$end";

                // Budujemy link wyboru (dla przycisku Choose)
                $chooseLink = "/reservation?room_id=" . htmlspecialchars($room['id']) . 
                              "&date=$date&start=$start&end=$end";
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