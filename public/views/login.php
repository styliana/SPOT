<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - Logowanie</title>
    
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
                
                <h2 class="page-title">Welcome to SPOT!</h2>

                <div class="toggle-buttons">
                    <a href="/login" class="toggle-btn active">Login</a>
                    <a href="/register" class="toggle-btn inactive">Register</a>
                </div>

                <p class="subtitle">Log in!!!</p>

                <form class="login-form" action="/login" method="POST">
                    
                    <?php if(isset($message)): ?>
                        <div class="message error"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="username">User name</label>
                        <input id="username" type="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input id="password" type="password" name="password" required>
                            </div>
                        </div>

                    <div class="form-extras">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="login-btn">Log in</button>
                </form>

            </div>
        </div>
    </main>

    <script src="/public/scripts/login.js" defer></script>
</body>
</html>