<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Admin - Pokoje</title>
    
    <?php include __DIR__ . '/components/global_head_links.html'; ?>
    <link rel="stylesheet" type="text/css" href="/public/styles/admin.css">

    <style>
        .search-wrapper { position: relative; width: 300px; }
        .search-wrapper input { width: 100%; padding: 12px 15px 12px 45px; border-radius: 50px; border: 1px solid #e5e7eb; background: white; outline: none; }
        .search-wrapper .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
    </style>
</head>
<body>
    
<?php include __DIR__ . '/components/header.php'; ?>

    <div class="admin-container">
        
        <div class="admin-card">
            <h3 class="form-title">Add New Room</h3>
            <?php if(isset($messages)): ?>
        <?php foreach($messages as $message): ?>
            <div style="color: #ef4444; margin-bottom: 1rem; font-weight: 600;">
                <?= $message; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <form action="/admin_rooms" method="POST" class="add-room-form">
        <div class="form-group">
            <label>ID (np. ROOM9)</label>
            <input type="text" name="id" placeholder="Unique ID" required pattern="[A-Z0-9]+" title="Only uppercase letters and numbers allowed">
        </div>
            <form action="/admin_rooms" method="POST" class="add-room-form">
                <div class="form-group"><label>ID (np. ROOM9)</label><input type="text" name="id" placeholder="Unique ID" required></div>
                <div class="form-group"><label>Name</label><input type="text" name="name" placeholder="Display Name" required></div>
                <div class="form-group"><label>Type</label><input type="text" name="type" placeholder="e.g. Lab" required></div>
                <div class="form-group"><label>Workspaces</label><input type="number" name="workspaces" placeholder="Capacity" required></div>
                <div class="form-group" style="grid-column: 1 / -1;"><label>Description</label><input type="text" name="description" placeholder="Short description..."></div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <button type="submit" class="submit-btn-admin"><span class="material-icons-outlined" style="vertical-align: middle;">add</span> Add Room</button>
                </div>
            </form>
        </div>
        
        <div class="admin-header-panel">
            <h2 class="page-title">Manage Rooms</h2>
            <div class="search-wrapper">
                <span class="material-icons-outlined search-icon">search</span>
                <input type="text" id="searchInput" placeholder="Search rooms...">
            </div>
        </div>

        <div class="table-container">
            <table id="roomsTable">
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Type</th><th>Seats</th><th>Description</th><th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $r): ?>
                    <tr>
                        <td data-label="ID" style="font-weight:bold; color:#6b7280;"><?= htmlspecialchars($r->getId()) ?></td>
                        <td data-label="Name" style="font-weight:500;"><?= htmlspecialchars($r->getName()) ?></td>
                        <td data-label="Type"><span class="pill pill-blue"><?= htmlspecialchars($r->getType()) ?></span></td>
                        <td data-label="Seats"><?= htmlspecialchars($r->getWorkspaces()) ?></td>
                        <td data-label="Description" style="color:#888; font-size:0.9rem;"><?= htmlspecialchars($r->getDescription()) ?></td>
                        <td data-label="Action" style="text-align: center;">
                            <form action="/admin_delete_room" method="POST" style="display:inline;" onsubmit="return confirm('Delete this room?');">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($r->getId()) ?>">
                                <button type="submit" class="icon-btn delete" title="Delete Room">
                                    <span class="material-icons-outlined">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <nav class="mobile-nav">
         <a href="/admin_users"><span class="material-icons-outlined">group</span>Users</a>
         <a href="/admin_rooms" class="active"><span class="material-icons-outlined">meeting_room</span>Rooms</a>
         <a href="/admin_bookings"><span class="material-icons-outlined">event_note</span>Bookings</a>
         <a href="/myprofile"><span class="material-icons-outlined">person</span>Profile</a>
         <a href="/logout"><span class="material-icons-outlined">logout</span>Exit</a>
    </nav>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#roomsTable tbody tr');
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>