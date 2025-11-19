<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Admin - Użytkownicy</title>
    <link rel="stylesheet" type="text/css" href="/public/styles/main.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body>
    <header class="main-header admin-header">
        <div class="nav-greeting">
            ADMIN PANEL <span class="admin-badge">MASTER MODE</span>
        </div>
        <nav class="main-nav">
             <ul>
                 <li><a href="/admin_users" class="nav-link" style="font-weight:bold;">Users</a></li>
                 <li><a href="/admin_rooms" class="nav-link">Rooms</a></li>
                 <li><a href="/admin_bookings" class="nav-link">Bookings</a></li>
                 <li><a href="/myprofile" class="nav-link">My Profile</a></li>
                 <li><a href="/logout" class="nav-link">Log out</a></li>
             </ul>
        </nav>
    </header>

    <main style="padding: 3rem; max-width: 1200px; margin: 0 auto;">
        <h2 class="page-title">Manage Users</h2>
        
        <div class="admin-table-container">
            <input type="text" id="searchInput" class="search-bar" placeholder="Szukaj użytkownika...">
            
            <table style="width: 100%; border-collapse: collapse;" id="adminTable">
                <thead>
                    <tr style="border-bottom: 2px solid #eee;">
                        <th style="text-align: left; padding: 10px;">ID</th>
                        <th style="text-align: left; padding: 10px;">Name</th>
                        <th style="text-align: left; padding: 10px;">Email</th>
                        <th style="text-align: left; padding: 10px;">Role</th>
                        <th style="text-align: center; padding: 10px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 10px;"><?= $u->getId() ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($u->getName() . ' ' . $u->getSurname()) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($u->getEmail()) ?></td>
                        <td style="padding: 10px;">
                            <form action="/admin_change_role" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $u->getId() ?>">
                                <select name="role" onchange="this.form.submit()" style="padding: 5px; border-radius: 5px;">
                                    <option value="student" <?= $u->getRole() == 'student' ? 'selected' : '' ?>>Student</option>
                                    <option value="teacher" <?= $u->getRole() == 'teacher' ? 'selected' : '' ?>>Teacher</option>
                                    <option value="admin" <?= $u->getRole() == 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td style="padding: 10px; text-align: center;">
                            <form action="/admin_delete_user" method="POST" onsubmit="return confirm('Usunąć?');">
                                <input type="hidden" name="id" value="<?= $u->getId() ?>">
                                <button type="submit" class="icon-btn delete" style="color: red; background: none; border: none; cursor: pointer;">
                                    <span class="material-icons-outlined">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        // Proste wyszukiwanie JS
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#adminTable tbody tr');
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>