<h2>User Management</h2>

<!-- Status message -->
<?php if (!empty($status['message'])): ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo $status['message'] ?>
    </div>
<?php endif; ?>

<!-- Create Button -->
<form action="/dashboard/users" method="POST">
    <button type="submit" class="btn btn-primary mb-3" name="action" value="create">Create New User</button>
</form>

<!-- Search Form -->
<form method="GET" action="/dashboard/users" class="mb-3 d-flex align-items-center">
    <input type="text" name="search" placeholder="Search users..." value="<?= htmlspecialchars($searchQuery) ?>"
        class="form-control d-inline-block w-auto me-2">

    <button type="submit" class="btn btn-primary me-2">Search</button>
    <?php if (!empty($searchQuery)): ?>
        <a href="/dashboard/users" class="btn btn-secondary text-white">Clear</a>
    <?php endif; ?>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <?php foreach ($columns as $column => $data): ?>
                <?php if ($data['sortable']): ?>
                    <?php
                    $newDirection = ($sortColumn == $column && $sortDirection == 'asc') ? 'desc' : 'asc';
                    $sortUrl = "?sort={$column}&direction={$newDirection}";
                    if (!empty($searchQuery)) {
                        $sortUrl .= "&search=" . htmlspecialchars($searchQuery);
                    }
                    ?>
                    <th>
                        <a href="<? $sortUrl ?>">
                            <?= $data['label'] ?>
                        </a>
                    </th>
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
                                <input type="text" name="firstname" value="<?= htmlspecialchars($user->firstname) ?>"
                                    class="form-control w-100" required>
                            <?php else: ?>
                                <?= htmlspecialchars($user->firstname) ?>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <input type="text" name="lastname" value="<?= htmlspecialchars($user->lastname) ?>"
                                    class="form-control w-100" required>
                            <?php else: ?>
                                <?= htmlspecialchars($user->lastname) ?>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id): ?>
                                <input type="email" name="email" value="<?= htmlspecialchars($user->email) ?>"
                                    class="form-control w-100" required>
                            <?php else: ?>
                                <?= htmlspecialchars($user->email) ?>
                            <?php endif; ?>
                        </td>

                        <td><?= htmlspecialchars($user->role->value) ?></td>

                        <td>
                            <?= htmlspecialchars($user->address) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($user->city) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($user->postal_code) ?>
                        </td>

                        <td class="text-nowrap"><?= $user->created_at ?></td>

                        <td>
                            <?= htmlspecialchars($user->stripe_customer_id) ?>
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
                <td colspan="<?= count($columns) ?>">No users found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>