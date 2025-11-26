<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - Rejestracja</title>
    
  <?php include __DIR__ . '/components/global_head_links.php'; ?>
    <link rel="stylesheet" type="text/css" href="/public/styles/login.css">
    
</head>
<body>
    <main class="login-page">
        <div class="left-pane">
            <img src="/public/images/uni.jpg" alt="University background" class="uni-image">
        </div>

        <div class="right-pane">
            <div class="form-container">
                
                <h2 class="page-title">Create Account</h2>

                <div class="toggle-buttons">
                    <a href="/login" class="toggle-btn inactive">Login</a>
                    <a href="/register" class="toggle-btn active">Register</a>
                </div>

                <p class="subtitle">Join the SPOT community!</p>

                <form class="login-form" id="register-form" action="/register" method="POST">
                    
                    <?php if(isset($message)): ?>
                        <div class="message error"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>
                    
                    <div class="message error js-error" id="js-error-message" style="display: none;"></div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="name">First Name</label>
                        <input id="name" type="text" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="surname">Last Name</label>
                        <input id="surname" type="text" name="surname" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input id="password" type="password" name="password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Confirm Password</label>
                        <div class="password-wrapper">
                            <input id="confirm-password" type="password" name="confirm_password" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="login-btn">Register</button>
                </form>

            </div>
        </div>
    </main>
    
    <script src="/public/scripts/register.js" defer></script>
</body>
</html>