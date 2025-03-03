<h2>User Management</h2>

<!-- Status message -->
<?php if (!empty($status['message'])): ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($status['message']); ?>
    </div>
<?php endif; ?>

<!-- Create Button -->
<form action="/dashboard/users" method="POST">
    <button type="submit" class="btn btn-primary mb-3" name="action" value="create">Create New User</button>
</form>

<!-- Search Form -->
<form method="GET" action="/dashboard/users" class="mb-3 d-flex align-items-center">
    <input type="text" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($searchQuery); ?>"
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
                <?php
                $newDirection = ($sortColumn == $column && $sortDirection == 'asc') ? 'desc' : 'asc';
                $sortUrl = "?sort={$column}&direction={$newDirection}";
                if (!empty($searchQuery)) {
                    $sortUrl .= '&search=' . htmlspecialchars($searchQuery);
                }
                ?>
                <th>
                    <?php if ($data['sortable']): ?>
                        <a href="<?php echo $sortUrl; ?>">
                            <?php echo htmlspecialchars($data['label']); ?>
                        </a>
                    <?php else: ?>
                        <?php echo htmlspecialchars($data['label']); ?>
                    <?php endif; ?>
                </th>
            <?php endforeach; ?>

            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr id="user-row-<?php echo htmlspecialchars($user->id); ?>">
                    <form action="/dashboard/users" method="POST">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user->id); ?>">

                        <?php
                        $isEditing = isset($_GET['edit']) && $_GET['edit'] == $user->id;

                        foreach ($columns as $columnKey => $columnData): ?>
                            <td>
                                <?php if ($columnData['editable'] && $isEditing): ?>

                                    <!-- Editable Input Fields -->
                                    <?php if (in_array($columnData['editable_type'], ['text', 'email'])): ?>
                                        <input type="<?php echo htmlspecialchars($columnData['editable_type']); ?>"
                                            name="<?php echo htmlspecialchars($columnKey); ?>"
                                            value="<?php echo htmlspecialchars($user->$columnKey); ?>" class="form-control w-100"
                                            <?php echo (!empty($columnData['required']) && $columnData['required']) ? 'required' : '' ?>>

                                        <!-- Role Select Dropdown -->
                                    <?php elseif ($columnData['editable_type'] === 'select'): ?>
                                        <select name="<?php echo htmlspecialchars($columnKey); ?>" class="form-control">
                                            <?php foreach ($columnData['options'] as $option): ?>
                                                <?php
                                                $userValue = $user->$columnKey instanceof \BackedEnum
                                                    ? $user->$columnKey->value
                                                    : $user->$columnKey;
                                                ?>
                                                <option value="<?php echo htmlspecialchars($option); ?>"
                                                    <?php echo ($userValue == $option) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars(ucfirst($option)); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <!-- Enum Display -->
                                    <?php
                                    $displayValue = $user->$columnKey instanceof \BackedEnum
                                        ? $user->$columnKey->value
                                        : (string) $user->$columnKey;
                                    ?>
                                    <?php echo htmlspecialchars(ucfirst($displayValue)); ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>

                        <!-- Actions -->
                        <td class="d-flex gap-2">
                            <?php if ($isEditing): ?>
                                <button type="submit" class="btn btn-success btn-sm" name="action" value="update">
                                    Confirm
                                </button>
                                <a href="/dashboard/users" class="btn btn-secondary btn-sm">
                                    Cancel
                                </a>
                            <?php else: ?>
                                <a href="/dashboard/users?edit=<?php echo htmlspecialchars($user->id); ?>"
                                    class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                                <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete">
                                    Delete
                                </button>
                            <?php endif; ?>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?php echo count($columns); ?>">No users found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>