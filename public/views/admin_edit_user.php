<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Admin - Edycja Użytkownika</title>

    <?php include __DIR__ . '/components/global_head_links.html'; ?>
    <link rel="stylesheet" type="text/css" href="/public/styles/admin.css">

</head>
<body>
    
<?php include __DIR__ . '/components/header.php'; ?>

    <div class="admin-container">
        <div class="edit-form-card">
            <h2 class="page-title" style="text-align:center; font-size: 1.8rem;">Edit User</h2>
            
            <form action="/admin_update_user" method="POST">
                <input type="hidden" name="id" value="<?= $user->getId() ?>">

                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user->getName()) ?>" required>
                </div>

                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="surname" value="<?= htmlspecialchars($user->getSurname()) ?>" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" required>
                </div>

                <button type="submit" class="btn-save">Save Changes</button>
                <a href="/admin_users" class="btn-cancel">Cancel</a>
            </form>
        </div>
    </div>

</body>
</html>