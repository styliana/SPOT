<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Admin - Pokoje</title>
    <link rel="stylesheet" type="text/css" href="/public/styles/main.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body>
    <header class="main-header admin-header">
        <div class="nav-greeting">ADMIN PANEL <span class="admin-badge">MASTER MODE</span></div>
        <nav class="main-nav">
             <ul>
                 <li><a href="/admin_users" class="nav-link">Users</a></li>
                 <li><a href="/admin_rooms" class="nav-link" style="font-weight:bold;">Rooms</a></li>
                 <li><a href="/admin_bookings" class="nav-link">Bookings</a></li>
                 <li><a href="/myprofile" class="nav-link">My Profile</a></li>
                 <li><a href="/logout" class="nav-link">Log out</a></li>
             </ul>
        </nav>
    </header>

    <main style="padding: 3rem; max-width: 1200px; margin: 0 auto;">
        <h2 class="page-title">Manage Rooms</h2>

        <div style="background: white; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem; border: 1px solid #ddd;">
            <h3>Add New Room</h3>
            <form action="/admin_rooms" method="POST" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; align-items: end;">
                <div class="form-group"><label>ID (np. ROOM9)</label><input type="text" name="id" required></div>
                <div class="form-group"><label>Name</label><input type="text" name="name" required></div>
                <div class="form-group"><label>Workspaces</label><input type="number" name="workspaces" required></div>
                <div class="form-group"><label>Type</label><input type="text" name="type" required></div>
                <div class="form-group" style="grid-column: span 2;"><label>Description</label><input type="text" name="description"></div>
                <button type="submit" class="submit-btn" style="height: 45px;">Add Room</button>
            </form>
        </div>
        
        <div class="admin-table-container">
            <table style="width: 100%; text-align: left;">
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Type</th><th>Seats</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $r): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;"><?= $r->getId() ?></td>
                        <td><?= $r->getName() ?></td>
                        <td><span class="pill pill-blue"><?= $r->getType() ?></span></td>
                        <td><?= $r->getWorkspaces() ?></td>
                        <td>
                            <form action="/admin_delete_room" method="POST" onsubmit="return confirm('Usunąć?');">
                                <input type="hidden" name="id" value="<?= $r->getId() ?>">
                                <button type="submit" style="color: red; border:none; background:none; cursor:pointer;"><span class="material-icons-outlined">delete</span></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>