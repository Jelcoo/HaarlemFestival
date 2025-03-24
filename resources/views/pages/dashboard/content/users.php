<!-- Title and Create Button -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>User Management</h2>
    <div>
        <a href="/dashboard/users/export" class="btn btn-success">Export to CSV</a>
        <a href="/dashboard/users/create" class="btn btn-primary">Create New User</a>
    </div>
</div>

<!-- Status message -->
<?php if (!empty($status['message'])) { ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($status['message']); ?>
    </div>
<?php } ?>

<!-- Search and Sort -->
<form method="GET" action="/dashboard/users" class="mb-3 d-flex justify-content-between align-items-center">
    <!-- Search -->
    <div class="d-flex align-items-center gap-2">
        <input type="text" name="search" placeholder="Search users..."
            value="<?php echo htmlspecialchars($searchQuery); ?>" class="form-control d-inline-block w-auto"
            style="max-width: 200px;">
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if (!empty($searchQuery)) { ?>
            <a href="/dashboard/users" class="btn btn-secondary text-white">Clear</a>
        <?php } ?>
    </div>

    <!-- Sort -->
    <div class="d-flex align-items-center gap-2">
        <select name="sort" id="sortSelect" class="form-select" style="width: 150px;">
            <option value="" disabled selected>Sort by...</option>
            <option value="firstname" <?php echo ($sortColumn == 'firstname') ? 'selected' : ''; ?>>First Name</option>
            <option value="lastname" <?php echo ($sortColumn == 'lastname') ? 'selected' : ''; ?>>Last Name</option>
            <option value="email" <?php echo ($sortColumn == 'email') ? 'selected' : ''; ?>>Email</option>
            <option value="role" <?php echo ($sortColumn == 'role') ? 'selected' : ''; ?>>Role</option>
            <option value="phone_number" <?php echo ($sortColumn == 'phone_number') ? 'selected' : ''; ?>>Phone Number</option>
            <option value="address" <?php echo ($sortColumn == 'address') ? 'selected' : ''; ?>>Address</option>
            <option value="city" <?php echo ($sortColumn == 'city') ? 'selected' : ''; ?>>City</option>
            <option value="postal_code" <?php echo ($sortColumn == 'postal_code') ? 'selected' : ''; ?>>Postal Code
            </option>
            <option value="created_at" <?php echo ($sortColumn == 'created_at') ? 'selected' : ''; ?>>Creation Date
            </option>
        </select>

        <select name="direction" id="directionSelect" class="form-select" style="width: 150px;">
            <option value="asc" <?php echo ($sortDirection == 'asc') ? 'selected' : ''; ?>>Ascending</option>
            <option value="desc" <?php echo ($sortDirection == 'desc') ? 'selected' : ''; ?>>Descending</option>
        </select>

        <button type="button" class="btn btn-primary" onclick="updateURL()">Apply</button>
        <button type="button" class="btn btn-secondary" onclick="resetSort()">Reset</button>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <?php foreach ($columns as $column => $data) { ?>
                <?php
                $newDirection = ($sortColumn == $column && $sortDirection == 'asc') ? 'desc' : 'asc';
                $sortUrl = "?sort={$column}&direction={$newDirection}";
                if (!empty($searchQuery)) {
                    $sortUrl .= '&search=' . htmlspecialchars($searchQuery);
                }
                ?>
                <th>
                    <?php if ($data['sortable']) { ?>
                        <a href="<?php echo $sortUrl; ?>">
                            <?php echo htmlspecialchars($data['label']); ?>
                        </a>
                    <?php } else { ?>
                        <?php echo htmlspecialchars($data['label']); ?>
                    <?php } ?>
                </th>
            <?php } ?>

            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($users)) { ?>
            <?php foreach ($users as $user) { ?>
                <tr id="user-row-<?php echo htmlspecialchars($user->id); ?>">

                    <?php foreach ($columns as $columnKey => $columnData) { ?>
                        <td>
                            <?php
                            $displayValue = $user->$columnKey instanceof BackedEnum
                                ? $user->$columnKey->value
                                : (string) $user->$columnKey;
                        ?>
                            <?php echo htmlspecialchars(ucfirst($displayValue)); ?>
                        </td>
                    <?php } ?>

                    <!-- Actions -->
                    <td class="d-flex gap-2">
                        <!-- Edit -->
                        <a href="/dashboard/users/edit?id=<?php echo $user->id; ?>" class="btn btn-warning btn-sm">Edit</a>

                        <!-- Delete -->
                        <form action="/dashboard/users/delete" method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?php echo $user->id; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="<?php echo count($columns); ?>">No users found.</td>
            </tr>
        <?php } ?>
    </tbody>

</table>

<script src="/assets/js/utils.js"></script>