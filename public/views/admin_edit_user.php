<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Admin - Edycja Użytkownika</title>

    <link rel="stylesheet" type="text/css" href="/public/styles/admin.css?v=10.0">
    <?php include __DIR__ . '/components/global_head_links.html'; ?>

    <style>
        body { background-color: #f3f4f6 !important; }
        .admin-container { max-width: 600px; margin: 3rem auto; padding: 0 1rem; min-height: 80vh; }
        
        .edit-form-card {
            background: white; padding: 3rem; border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: #6b7280; font-size: 0.9rem; font-weight: 600; }
        .form-group input {
            width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #e5e7eb;
            background-color: #f9fafb; outline: none; transition: 0.2s; font-family: 'Poppins';
        }
        .form-group input:focus { border-color: #0A6BEF; background: white; box-shadow: 0 0 0 3px rgba(10,107,239,0.1); }
        
        .btn-save {
            background-color: #0A6BEF; color: white; border: none; padding: 12px 24px; border-radius: 50px;
            font-weight: 600; cursor: pointer; transition: 0.2s; width: 100%; font-size: 1rem; margin-top: 1rem;
        }
        .btn-save:hover { background-color: #0855BB; transform: translateY(-2px); }
        
        .btn-cancel {
            display: block; text-align: center; margin-top: 1rem; color: #9ca3af; text-decoration: none; font-size: 0.9rem;
        }
    </style>
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