<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPOT - About Us</title>

    <?php include __DIR__ . '/components/global_head_links.html'; ?>
    <link rel="stylesheet" type="text/css" href="public/styles/about.css">

</head>
<body>

<?php include __DIR__ . '/components/header.php'; ?>

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