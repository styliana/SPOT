<?php

if (!isset($_SESSION)) {
    session_start();
}

$isAdmin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');

function isActive($path) {
    return (strpos($_SERVER['REQUEST_URI'], $path) !== false) ? 'active' : '';
}

$isHomePage = ($_SERVER['REQUEST_URI'] === '/');

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
                 <li><a href="/admin_users" class="nav-link <?php echo (isActive('admin_users') || $isHomePage) ? 'active' : ''; ?>">Users</a></li>
                 <li><a href="/admin_rooms" class="nav-link <?php echo isActive('admin_rooms'); ?>">Rooms</a></li>
                 <li><a href="/admin_bookings" class="nav-link <?php echo isActive('admin_bookings'); ?>">Bookings</a></li>
                    <li><a href="/myprofile" class="nav-link <?php echo isActive('myprofile'); ?>">My Profile</a></li>
             <?php else: ?>
                 <li><a href="/about" class="nav-link <?php echo isActive('about'); ?>">About</a></li>
                 <li><a href="/mybookings" class="nav-link <?php echo (isActive('mybookings') || $isHomePage) ? 'active' : ''; ?>">My bookings</a></li>
                 <li><a href="/myprofile" class="nav-link <?php echo isActive('myprofile'); ?>">My profile</a></li>
             <?php endif; ?>
             <li><a href="/logout" class="nav-link">Log out</a></li>
         </ul>
    </nav>
    
    <nav class="mobile-nav">
        <?php if ($isAdmin): ?>
             <a href="/admin_users" class="<?php echo (isActive('admin_users') || $isHomePage) ? 'active' : ''; ?>"><span class="material-icons-outlined">group</span></a>
             <a href="/admin_bookings" class="<?php echo isActive('admin_bookings'); ?>"><span class="material-icons-outlined">event_note</span></a>
        <?php else: ?>
             <a href="/myprofile" class="<?php echo isActive('myprofile'); ?>"><span class="material-icons-outlined">person</span></a>
             <a href="/mybookings" class="<?php echo (isActive('mybookings') || $isHomePage) ? 'active' : ''; ?>"><span class="material-icons-outlined">description</span></a>
        <?php endif; ?>
         <a href="/logout"><span class="material-icons-outlined">logout</span></a>
    </nav>
</header>