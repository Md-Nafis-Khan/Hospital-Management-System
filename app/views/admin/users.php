<?php
$title = 'Users';
require 'app/views/partials/header.php';
?>
<h1>Users</h1>

<div class="table">
    <table>
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        <?php foreach ($items as $x): ?>
            <tr>
                <td><?= htmlspecialchars($x['full_name']) ?></td>
                <td><?= htmlspecialchars($x['username']) ?></td>
                <td><?= htmlspecialchars($x['email']) ?></td>
                <td><?= htmlspecialchars($x['phone']) ?></td>
                <td><?= htmlspecialchars($x['user_type']) ?></td>
                <td>
                    <?php if ($x['id'] != $_SESSION['user_id']): ?>
                        <form method="post" action="?action=admin_delete_user" data-confirm="Delete this user?">
                            <input type="hidden" name="user_id" value="<?= $x['id'] ?>">
                            <button type="submit" class="danger">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php if (!$items): ?>
        <p>No users found.</p>
    <?php endif; ?>
</div>

<?php require 'app/views/partials/footer.php'; ?>
