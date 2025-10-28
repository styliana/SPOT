<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - About Us</title>

    <link rel="stylesheet" type="text/css" href="public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="public/styles/about.css">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>

    <header class="main-header">
        <nav class="main-nav">
             <ul>
                 <li><a href="/about" class="nav-link">About</a></li>
                 <li><a href="/mybookings" class="nav-link">My bookings</a></li>
                 <li><a href="/myprofile" class="nav-link">My profile</a></li>
                 <li><a href="/login" class="nav-link">Log out</a></li>
             </ul>
        </nav>
        <nav class="mobile-nav">
             <a href="/myprofile"><span class="material-icons-outlined">person_outline</span></a>
             <a href="/mybookings"><span class="material-icons-outlined">description</span></a>
             <a href="/login"><span class="material-icons-outlined">logout</span></a>
        </nav>
    </header>

    <main class="about-page-content">
        <div class="hero-section">
            <h1 class="about-title">
                What is <span class="spot-highlight">SPOT</span>? ✨
            </h1>
            <p class="tagline">Smart Place Organization Tool | "Your campus. Your space. Your SPOT."</p>
        </div>

        <div class="about-sections-wrapper">
            <section class="about-section">
                <p class="about-text">
                    Say goodbye to booking conflicts, uncertainty, and the hassle of manually searching for available rooms. <span class="spot-bold">SPOT (Smart Place Organization Tool)</span> is a revolutionary platform for academic space management, designed for the prestige and dynamism of the modern university.
                </p>
            </section>

             <hr class="section-separator"> <section class="about-section">
                 <h2 class="section-heading"><span class="material-icons-outlined section-icon">integration_instructions</span>How does it work?</h2>
                 <p class="about-text">
                     SPOT combines an elegant interface with a powerful organizational engine that automates the booking process for rooms, labs, and lecture halls. It's a tool that brings order and peace of mind.
                 </p>
             </section>

             <hr class="section-separator">

            <section class="about-section">
                <h2 class="section-heading"><span class="material-icons-outlined section-icon">rocket_launch</span>Our mission</h2>
                 <p class="about-text mission-intro">
                     Allowing the entire academic community to focus on what matters most:
                 </p>
                <div class="mission-points">
                    <div class="mission-item">
                        <span class="material-icons-outlined mission-icon">school</span>
                        <p>Learning</p>
                    </div>
                    <div class="mission-item">
                        <span class="material-icons-outlined mission-icon">workspace_premium</span>
                        <p>Development</p>
                    </div>
                    <div class="mission-item">
                        <span class="material-icons-outlined mission-icon">diversity_3</span>
                        <p>Collaboration!</p>
                    </div>
                </div>
            </section>
        </div>
    </main>

</body>
</html>