<h2>User Management</h2>

<!-- Status message -->
<?php if (isset($status) && $status): ?>
    <div class="alert alert-<?php echo $status === 'success' ? 'success' : 'danger'; ?>">
        <?php echo $status === 'success' ? 'Action completed successfully.' : 'There was an error processing the action.'; ?>
    </div>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Adress</th>
            <th>City</th>
            <th>Postal Code</th>
            <th>Created At</th>
            <th>Stripe ID</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr id="user-row-<?= $user->id ?>">
                    <form action="/dashboard/users" method="POST">
                        <input type="hidden" name="id" value="<?= $user->id ?>">

                        <td><?= $user->id ?></td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <input type="text" name="firstname" value="<?= $user->firstname ?>" required>
                            <?php else: ?>
                                <?= $user->firstname ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <input type="text" name="lastname" value="<?= $user->lastname ?>" required>
                            <?php else: ?>
                                <?= $user->lastname ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <input type="email" name="email" value="<?= $user->email ?>" required>
                            <?php else: ?>
                                <?= $user->email ?>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <select name="role" required>
                                    <option value="ADMIN" <?= $user->role->value == 'ADMIN' ? 'selected' : '' ?>>Admin</option>
                                    <option value="USER" <?= $user->role->value == 'USER' ? 'selected' : '' ?>>User</option>
                                </select>
                            <?php else: ?>
                                <?= $user->role->value ?>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <input type="text" name="address" value="<?= $user->address ?>" required>
                            <?php else: ?>
                                <?= $user->address ?>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <input type="text" name="city" value="<?= $user->city ?>" required>
                            <?php else: ?>
                                <?= $user->city ?>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <input type="text" name="postal_code" value="<?= $user->postal_code ?>" required>
                            <?php else: ?>
                                <?= $user->postal_code ?>
                            <?php endif; ?>
                        </td>

                        <td><?= $user->created_at ?></td>
                        <td><?= $user->stripe_customer_id ?></td>

                        <td>
                            <!-- Edit/Update Button -->
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <button type="submit" class="btn btn-success btn-sm" name="action" value="update">Update</button>
                            <?php else: ?>
                                <a href="/dashboard/users?edit=<?= $user->id ?>" class="btn btn-warning btn-sm">Edit</a>
                            <?php endif; ?>

                            <!-- Delete Button -->
                            <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete">Delete</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No users found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>