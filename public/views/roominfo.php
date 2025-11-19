<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - <?php echo htmlspecialchars($room['name']); ?></title>
    
    <link rel="stylesheet" type="text/css" href="/public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="/public/styles/room_info.css">
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
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
            <div class="back-link-wrapper">
                <a href="/reservation" class="back-button" aria-label="Wróć">
                    <span class="material-icons-outlined">arrow_back</span>
                </a>
            </div>

            <div class="room-header">
                <span class="material-icons-outlined room-icon">meeting_room</span>
                <h2 class="room-name"><?php echo htmlspecialchars($room['name']); ?></h2>
                
                <div class="room-type-badge">
                    <span class="pill pill-blue"><?php echo htmlspecialchars($room['type']); ?></span>
                </div>
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

            <a href="/reservation?room_id=<?php echo htmlspecialchars($room['id']); ?>" class="choose-room-button">
                Choose this room
            </a>
        </div>

    </main>
</body>
</html>