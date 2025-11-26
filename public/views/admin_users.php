<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Użytkownicy</title>
    
    <?php include __DIR__ . '/components/global_head_links.php'; ?>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
 

    <style>
        body { background-color: #f3f4f6 !important; }
        .admin-header { background-color: #1f2937 !important; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .admin-badge { background: #ef4444; color: white; padding: 3px 8px; border-radius: 6px; font-size: 0.7rem; margin-left: 8px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }
        .nav-link.active { color: #60a5fa !important; font-weight: 700; position: relative; }
        .nav-link.active::after { content: ''; position: absolute; bottom: -22px; left: 0; width: 100%; height: 3px; background-color: #60a5fa; }
        .admin-container { max-width: 1300px; margin: 3rem auto; padding: 0 2rem; padding-bottom: 100px; }
        .admin-header-panel { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .page-title { font-size: 2rem; color: #1f2937; font-weight: 800; margin: 0; }
        
        .search-wrapper { position: relative; width: 320px; }
        .search-wrapper input { width: 100%; padding: 12px 15px 12px 45px; border-radius: 50px; border: 1px solid #e5e7eb; background-color: white; font-family: 'Poppins', sans-serif; font-size: 0.9rem; outline: none; box-shadow: 0 2px 8px rgba(0,0,0,0.03); transition: all 0.3s ease; }
        .search-wrapper input:focus { border-color: #0A6BEF; box-shadow: 0 4px 15px rgba(10, 107, 239, 0.15); width: 340px; }
        .search-wrapper .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 22px; }

        .table-container { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid rgba(0,0,0,0.02); }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #fff; padding: 1.5rem 1.5rem; text-align: left; font-weight: 700; color: #6b7280; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 2px solid #f3f4f6; cursor: pointer; }
        td { padding: 1.2rem 1.5rem; border-bottom: 1px solid #f3f4f6; color: #374151; font-size: 0.95rem; vertical-align: middle; }
        tr:hover { background-color: #f8fafc; }

        .actions-cell { display: flex; gap: 10px; justify-content: center; align-items: center; }
        .icon-btn { border: none; background: #f3f4f6; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; color: #6b7280; text-decoration: none; }
        .icon-btn:hover { transform: translateY(-2px); color: white; }
        .icon-btn.edit:hover { background-color: #0A6BEF; }
        .icon-btn.delete:hover { background-color: #ef4444; }

        .role-select { padding: 5px 10px; border-radius: 8px; border: 1px solid #e5e7eb; font-family: 'Roboto', sans-serif; font-size: 0.9rem; cursor: pointer; background-color: #f9fafb; color: #374151; outline: none; }
        .role-select:focus { border-color: #0A6BEF; }

        @media (max-width: 850px) {
            .admin-container { padding: 1rem; margin: 1rem auto; }
            .main-nav { display: none !important; }
            .table-container { background: transparent; box-shadow: none; border: none; overflow: visible; }
            thead { display: none; }
            tr { display: flex; flex-direction: column; background: white; margin-bottom: 1.5rem; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 1.5rem; border: 1px solid #eee; }
            td { display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 0; border-bottom: 1px dashed #f0f0f0; text-align: right; }
            td:last-child { border-bottom: none; padding-top: 1.5rem; justify-content: flex-end; }
            td::before { content: attr(data-label); font-weight: 700; font-size: 0.75rem; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; }
            .mobile-nav { display: flex !important; position: fixed; bottom: 0; left: 0; width: 100%; background-color: #1f2937; padding: 10px 15px; justify-content: space-between; align-items: center; z-index: 9999; }
            .mobile-nav a { color: #9ca3af; text-decoration: none; display: flex; flex-direction: column; align-items: center; font-size: 0.7rem; width: 60px; }
            .mobile-nav a.active { color: #60a5fa; }
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/components/header.php'; ?>

    <div class="admin-container">
        <div class="admin-header-panel">
            <h2 class="page-title" style="margin:0;">Manage Users</h2>
            <div class="search-wrapper">
                <span class="material-icons-outlined search-icon">search</span>
                <input type="text" id="searchInput" placeholder="Search users...">
            </div>
        </div>
        
        <div class="table-container">
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th><th>Full Name</th><th>Email</th><th>Role</th><th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td data-label="ID" style="font-weight:bold; color:#9ca3af;"><?= $u->getId() ?></td>
                        <td data-label="Full Name" style="font-weight:500;"><?= htmlspecialchars($u->getName() . ' ' . $u->getSurname()) ?></td>
                        <td data-label="Email"><?= htmlspecialchars($u->getEmail()) ?></td>
                        <td data-label="Role">
                            <form action="/admin_change_role" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $u->getId() ?>">
                                <select name="role" onchange="this.form.submit()" class="role-select">
                                    <option value="student" <?= $u->getRole() == 'student' ? 'selected' : '' ?>>Student</option>
                                    <option value="teacher" <?= $u->getRole() == 'teacher' ? 'selected' : '' ?>>Teacher</option>
                                    <option value="admin" <?= $u->getRole() == 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td data-label="Action" class="actions-cell">
                            <a href="/admin_edit_user?id=<?= $u->getId() ?>" class="icon-btn edit" title="Edit User">
                                <span class="material-icons-outlined">edit</span>
                            </a>
                            <form action="/admin_delete_user" method="POST" style="display:inline;" onsubmit="return confirm('Usunąć użytkownika?');">
                                <input type="hidden" name="id" value="<?= $u->getId() ?>">
                                <button type="submit" class="icon-btn delete" title="Delete User">
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
         <a href="/admin_users" class="active"><span class="material-icons-outlined">group</span>Users</a>
         <a href="/admin_rooms"><span class="material-icons-outlined">meeting_room</span>Rooms</a>
         <a href="/admin_bookings"><span class="material-icons-outlined">event_note</span>Bookings</a>
         <a href="/myprofile"><span class="material-icons-outlined">person</span>Profile</a>
         <a href="/logout"><span class="material-icons-outlined">logout</span>Exit</a>
    </nav>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#usersTable tbody tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>