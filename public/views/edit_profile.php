<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - Edytuj Profil</title>
    
    <?php include __DIR__ . '/components/global_head_links.html'; ?>
    <link rel="stylesheet" type="text/css" href="/public/styles/login.css">
    
    <style>
        .edit-profile-wrapper {
            display: flex;
            justify-content: center;
            padding: 4rem 1rem;
            min-height: 80vh;
        }
        .edit-form-container {
            background: white;
            padding: 2.5rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 500px;
        }
        .section-label {
            font-size: 0.9rem;
            color: var(--primary-blue);
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/components/header.php'; ?>

    <main class="edit-profile-wrapper">
        <div class="edit-form-container">
            <h2 class="page-title">Edit Profile</h2>
            
            <form action="/edit_profile" method="POST">
                
                <?php if(isset($message)): ?>
                    <div class="message error"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

                <div class="section-label">Personal Info</div>

                <div class="form-group">
                    <label for="email">Email (read-only)</label>
                    <input id="email" type="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>" readonly disabled style="background-color: #f3f4f6; color: #6b7280;">
                </div>

                <div class="form-group">
                    <label for="name">First Name</label>
                    <input id="name" name="name" type="text" value="<?php echo htmlspecialchars($user->getName()); ?>" required>
                </div>

                <div class="form-group">
                    <label for="surname">Last Name</label>
                    <input id="surname" name="surname" type="text" value="<?php echo htmlspecialchars($user->getSurname()); ?>" required>
                </div>

                <div class="section-label">Change Password (Optional)</div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input id="new_password" name="new_password" type="password" placeholder="Leave empty to keep current">
                </div>

                <div class="form-group">
                    <label for="confirm_new_password">Confirm New Password</label>
                    <input id="confirm_new_password" name="confirm_new_password" type="password" placeholder="Confirm new password">
                </div>

                <div class="section-label" style="color: #d32f2f;">Authorization Required</div>

                <div class="form-group">
                    <label for="old_password" style="color: #d32f2f;">Current Password (Required to save)</label>
                    <input id="old_password" name="old_password" type="password" required style="border-color: #d32f2f;">
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <a href="/myprofile" class="submit-btn" style="background-color: #9ca3af; text-decoration: none; display: flex; align-items: center; justify-content: center;">Cancel</a>
                    <button type="submit" class="submit-btn">Save Changes</button>
                </div>

            </form>
        </div>
    </main>

</body>
</html>