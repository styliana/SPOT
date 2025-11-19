<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - Mój Profil</title>
    <link rel="stylesheet" type="text/css" href="/public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="/public/styles/profile.css">
    <link rel="stylesheet" type="text/css" href="/public/styles/admin.css">
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    
    <?php
        // Sprawdzamy, czy zalogowany jest admin
        $isAdmin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');
    ?>

    <header class="main-header <?php echo $isAdmin ? 'admin-header' : ''; ?>">
        
        <div class="nav-greeting">
            <?php if ($isAdmin): ?>
                ADMIN PANEL <span class="admin-badge">MASTER</span>
            <?php else: ?>
                <?php if (isset($_SESSION['user_name'])): ?>
                    Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <nav class="main-nav">
             <ul>
                 <?php if ($isAdmin): ?>
                     <li><a href="/admin_users" class="nav-link">Users</a></li>
                     <li><a href="/admin_rooms" class="nav-link">Rooms</a></li>
                     <li><a href="/admin_bookings" class="nav-link">Bookings</a></li>
                     <li><a href="/myprofile" class="nav-link active" style="font-weight:bold;">My Profile</a></li>
                 <?php else: ?>
                     <li><a href="/about" class="nav-link">About</a></li>
                     <li><a href="/mybookings" class="nav-link">My bookings</a></li>
                     <li><a href="/myprofile" class="nav-link">My profile</a></li>
                 <?php endif; ?>
                 <li><a href="/logout" class="nav-link">Log out</a></li>
             </ul>
        </nav>
        
        <nav class="mobile-nav">
            <?php if ($isAdmin): ?>
                 <a href="/admin_users"><span class="material-icons-outlined">group</span></a>
                 <a href="/admin_bookings"><span class="material-icons-outlined">event_note</span></a>
            <?php else: ?>
                 <a href="/myprofile"><span class="material-icons-outlined">person</span></a>
                 <a href="/mybookings"><span class="material-icons-outlined">description</span></a>
            <?php endif; ?>
             <a href="/logout"><span class="material-icons-outlined">logout</span></a>
        </nav>
    </header>

    <main class="profile-page-content">
        
        <?php if(isset($message)): ?>
            <div class="message success" style="background-color: #d1fae5; color: #067647; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <h2 class="page-title">My Profile</h2>

        <div class="profile-container">
            <div class="profile-card">
                
                <div class="profile-avatar">
                    <span class="material-icons-outlined avatar-placeholder">account_circle</span>
                </div>

                <h3 class="user-name">
                    <?php echo htmlspecialchars($user->getName()) . ' ' . htmlspecialchars($user->getSurname()); ?>
                </h3>
                <p class="user-email"><?php echo htmlspecialchars($user->getEmail()); ?></p>

                <hr class="profile-separator">

                <div class="profile-details">
                    <div class="detail-item">
                        <span class="detail-label">Role:</span>
                        <span class="detail-value">
                            <?php 
                                $role = $user->getRole();
                                $pillClass = 'pill-blue';
                                if ($role === 'admin') $pillClass = 'pill-red';
                                if ($role === 'teacher') $pillClass = 'pill-purple'; // fioletowy (trzeba dodać w CSS jeśli chcesz)
                            ?>
                            <span class="pill <?php echo $pillClass; ?>"><?php echo htmlspecialchars(ucfirst($role)); ?></span>
                        </span>
                    </div>
                </div>

                <a href="/edit_profile" class="edit-profile-button" style="text-decoration: none; justify-content: center;">
                     <span class="material-icons-outlined">edit</span> Edit Profile
                </a> 
            </div>
        </div>
    </main>
</body>
</html>