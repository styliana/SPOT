<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Admin - Pokoje</title>
    
    <?php include __DIR__ . '/components/global_head_links.php'; ?>

    <style>
        /* === STYLE ADMINA === */
        body { background-color: #f3f4f6 !important; }
        .admin-header { background-color: #1f2937 !important; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .admin-badge { background: #ef4444; color: white; padding: 3px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; margin-left: 8px; }
        .nav-link.active { color: #60a5fa !important; font-weight: 700; position: relative; }
        .nav-link.active::after { content: ''; position: absolute; bottom: -22px; left: 0; width: 100%; height: 3px; background-color: #60a5fa; }
        
        .admin-container { max-width: 1300px; margin: 3rem auto; padding: 0 2rem; padding-bottom: 100px; }
        
        /* Formularz w Karcie */
        .admin-card { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 2rem; margin-bottom: 2rem; }
        .form-title { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem; }
        .add-room-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; align-items: end; }
        .form-group { margin-bottom: 0; }
        .form-group label { font-size: 0.85rem; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; display: block; }
        .form-group input { width: 100%; padding: 10px 15px; border-radius: 12px; border: 1px solid #e5e7eb; background: #f9fafb; outline: none; transition: 0.2s; }
        .form-group input:focus { border-color: #0A6BEF; background: white; }
        .submit-btn-admin { background-color: #0A6BEF; color: white; border: none; padding: 10px 20px; border-radius: 12px; font-weight: 600; cursor: pointer; height: 42px; width: 100%; }
        .submit-btn-admin:hover { background-color: #0855BB; transform: translateY(-2px); }

        /* Tabela */
        .admin-header-panel { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; gap: 1rem; }
        .search-wrapper { position: relative; width: 300px; }
        .search-wrapper input { width: 100%; padding: 12px 15px 12px 45px; border-radius: 50px; border: 1px solid #e5e7eb; background: white; outline: none; }
        .search-wrapper .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
        
        .table-container { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #fff; padding: 1rem; text-align: left; font-weight: 700; color: #6b7280; border-bottom: 2px solid #f3f4f6; cursor: pointer; }
        td { padding: 1rem; border-bottom: 1px solid #f3f4f6; color: #374151; vertical-align: middle; }
        tr:hover { background-color: #f8fafc; }

        .icon-btn { border: none; background: #f3f4f6; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #6b7280; }
        .icon-btn:hover { transform: translateY(-2px); color: white; }
        .icon-btn.delete:hover { background-color: #ef4444; }
        .pill { padding: 5px 12px; border-radius: 15px; font-size: 0.85rem; font-weight: 600; display: inline-block; }
        .pill-blue { background-color: #E0EEFF; color: #0A6BEF; }

        @media (max-width: 850px) {
            .admin-container { padding: 1rem; }
            .main-nav { display: none !important; }
            .add-room-form { grid-template-columns: 1fr; }
            .table-container { background: transparent; box-shadow: none; }
            thead { display: none; }
            tr { display: flex; flex-direction: column; background: white; margin-bottom: 1rem; border-radius: 16px; padding: 1.5rem; border: 1px solid #eee; }
            td { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px dashed #f0f0f0; text-align: right; }
            td:last-child { border-bottom: none; justify-content: flex-end; }
            td::before { content: attr(data-label); font-weight: 700; font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; }
            
            .mobile-nav { display: flex !important; position: fixed; bottom: 0; left: 0; width: 100%; background: #1f2937; padding: 10px 15px; justify-content: space-between; z-index: 9999; }
            .mobile-nav a { color: #9ca3af; text-decoration: none; display: flex; flex-direction: column; align-items: center; font-size: 0.7rem; width: 60px; }
            .mobile-nav a.active { color: #60a5fa; }
        }
    </style>
</head>
<body>
    
<?php include __DIR__ . '/components/header.php'; ?>

    <div class="admin-container">
        
        <div class="admin-card">
            <h3 class="form-title">Add New Room</h3>
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