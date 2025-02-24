<h2>User Management</h2>

<!-- Status message -->
<?php if (!empty($status['message'])) { ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo $status['message']; ?>
    </div>
<?php } ?>

<!-- Create Button -->
<form action="/dashboard/users" method="POST">
    <button type="submit" class="btn btn-primary mb-3" name="action" value="create">Create New User</button>
</form>

<!-- Search Form -->
<form method="GET" action="/dashboard/users" class="mb-3 d-flex align-items-center">
    <input type="text" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($searchQuery); ?>"
        class="form-control d-inline-block w-auto me-2">

    <button type="submit" class="btn btn-primary me-2">Search</button>
    <?php if (!empty($searchQuery)) { ?>
        <a href="/dashboard/users" class="btn btn-secondary text-white">Clear</a>
    <?php } ?>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <?php foreach ($columns as $column => $data) { ?>
                <?php if ($data['sortable']) { ?>
                    <?php
                    $newDirection = ($sortColumn == $column && $sortDirection == 'asc') ? 'desc' : 'asc';
                    $sortUrl = "?sort={$column}&direction={$newDirection}";
                    if (!empty($searchQuery)) {
                        $sortUrl .= '&search=' . htmlspecialchars($searchQuery);
                    }
                    ?>
                    <th>
                        <a href="<?php echo $sortUrl; ?>">
                            <?php echo $data['label']; ?>
                        </a>
                    </th>
                <?php } else { ?>
                    <th><?php echo $data['label']; ?></th>
                <?php } ?>
            <?php } ?>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($users)) { ?>
            <?php foreach ($users as $user) { ?>
                <tr id="user-row-<?php echo $user->id; ?>">
                    <form action="/dashboard/users" method="POST">
                        <input type="hidden" name="id" value="<?php echo $user->id; ?>">

                        <td><?php echo $user->id; ?></td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id) { ?>
                                <input type="text" name="firstname" value="<?php echo htmlspecialchars($user->firstname); ?>"
                                    class="form-control w-100" required>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($user->firstname); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id) { ?>
                                <input type="text" name="lastname" value="<?php echo htmlspecialchars($user->lastname); ?>"
                                    class="form-control w-100" required>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($user->lastname); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id) { ?>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>"
                                    class="form-control w-100" required>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($user->email); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id) { ?>
                                <select name="role" required>
                                    <option value="ADMIN" <?php echo $user->role->value == 'ADMIN' ? 'selected' : ''; ?>>Admin</option>
                                    <option value="USER" <?php echo $user->role->value == 'USER' ? 'selected' : ''; ?>>User</option>
                                </select>
                            <?php } else { ?>
                                <?php echo $user->role->value; ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id) { ?>
                                <input type="text" name="address" value="<?php echo htmlspecialchars($user->address); ?>"
                                    class="form-control w-100" required>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($user->address); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id) { ?>
                                <input type="text" name="city" value="<?php echo htmlspecialchars($user->city); ?>"
                                    class="form-control w-100" required>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($user->city); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id) { ?>
                                <input type="text" name="postal_code" value="<?php echo htmlspecialchars($user->postal_code); ?>"
                                    class="form-control w-100" required>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($user->postal_code); ?>
                            <?php } ?>
                        </td>

                        <td class="text-nowrap"><?php echo $user->created_at; ?></td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id) { ?>
                                <input type="text" name="stripe_customer_id"
                                    value="<?php echo htmlspecialchars($user->stripe_customer_id); ?>" class="form-control w-100" required>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($user->stripe_customer_id); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <!-- Edit/Update Button -->
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $user->id) { ?>
                                <button type="submit" class="btn btn-success btn-sm" name="action" value="update">Update</button>
                            <?php } else { ?>
                                <a href="/dashboard/users?edit=<?php echo $user->id; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <?php } ?>

                            <!-- Delete Button -->
                            <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete">Delete</button>
                        </td>
                    </form>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="<?php echo count($columns); ?>">No users found.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>