<h2>User Management</h2>
<a href="create.php" class="btn btn-primary mb-3">Add New User</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td><?= $user->firstname ?></td>
                    <td><?= $user->lastname ?></td>
                    <td><?= $user->email ?></td>
                    <td><?= $user->role->value ?></td>
                    <td><?= $user->created_at ?></td>
                    <td>
                        <form action="/dashboard/users" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $user->id ?>">

                            <!-- Edit Button -->
                            <button type="submit" class="btn btn-warning btn-sm" name="action" value="edit">Edit</button>

                            <!-- Delete Button -->
                            <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No users found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>