<?php

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - Mój Profil</title>

    <?php include __DIR__ . '/components/global_head_links.php'; ?>
    <link rel="stylesheet" type="text/css" href="/public/styles/profile.css">
    <link rel="stylesheet" type="text/css" href="/public/styles/admin.css">
    
</head>
<body>

<?php 
include __DIR__ . '/components/header.php'; 
?>

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
                            if ($role === 'teacher') $pillClass = 'pill-purple';
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