<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - Mój Profil</title>
    <link rel="stylesheet" type="text/css" href="public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="public/styles/profile.css">
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
                            <span class="pill pill-blue"><?php echo htmlspecialchars(ucfirst($user->getRole())); ?></span>
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