<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - Informacje o Pokoju: <?php echo htmlspecialchars($room['name']); ?></title>
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
             <a href="#"><span class="material-icons-outlined">person_outline</span></a>
             <a href="/mybookings"><span class="material-icons-outlined">description</span></a>
             <a href="/logout"><span class="material-icons-outlined">logout</span></a>
        </nav>
    </header>
    <main class="room-info-page-content">
        <a href="javascript:history.back()" class="back-button">
            <span class="material-icons-outlined">arrow_back_ios</span>
        </a>
        <h2 class="page-title"><?php echo htmlspecialchars($room['name']); ?></h2>
        <div class="info-table-container">
            <table>
                <thead>
                    <tr>
                        <th>Workspaces</th>
                        <th>Type</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Workspaces"><?php echo htmlspecialchars($room['workspaces']); ?></td>
                        <td data-label="Type">
                             <span class="pill pill-blue"><?php echo htmlspecialchars($room['type']); ?></span> 
                        </td>
                        <td data-label="Description"><?php echo htmlspecialchars($room['description']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button class="choose-room-button">Choose this room</button>
    </main>
</body>
</html>