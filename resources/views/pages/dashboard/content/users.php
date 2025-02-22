<h2>User Management</h2>

<!-- Status message -->
<?php if (!empty($status)): ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo $status['message'] ?>
    </div>
<?php endif; ?>

<!-- Create Button -->
<form action="/dashboard/users" method="POST">
    <button type="submit" class="btn btn-primary mb-3" name="action" value="create">Create New User</button>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <?php foreach ($columns as $column => $data): ?>
                <?php if ($data['sortable']): ?>
                    <?php $newDirection = ($sortColumn == $column && $sortDirection == 'asc') ? 'desc' : 'asc'; ?>
                    <th><a href="?sort=<?= $column ?>&direction=<?= $newDirection ?>"><?= $data['label'] ?></a></th>
                <?php else: ?>
                    <th><?= $data['label'] ?></th>
                <?php endif; ?>
            <?php endforeach; ?>
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
                                <input type="text" name="firstname" value="<?= $user->firstname ?>" class="form-control w-100"
                                    required>
                            <?php else: ?>
                                <?= $user->firstname ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <input type="text" name="lastname" value="<?= $user->lastname ?>" class="form-control w-100"
                                    required>
                            <?php else: ?>
                                <?= $user->lastname ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <input type="email" name="email" value="<?= $user->email ?>" class="form-control w-100" required>
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
                                <input type="text" name="address" value="<?= $user->address ?>" class="form-control w-100">
                            <?php else: ?>
                                <?= $user->address ?>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <input type="text" name="city" value="<?= $user->city ?>" class="form-control w-100">
                            <?php else: ?>
                                <?= $user->city ?>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <input type="text" name="postal_code" value="<?= $user->postal_code ?>" class="form-control w-100">
                            <?php else: ?>
                                <?= $user->postal_code ?>
                            <?php endif; ?>
                        </td>

                        <td class="text-nowrap"><?= $user->created_at ?></td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <input type="text" name="stripe_customer_id" value="<?= $user->stripe_customer_id ?>"
                                    class="form-control w-100">
                            <?php else: ?>
                                <?= $user->stripe_customer_id ?>
                            <?php endif; ?>
                        </td>

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