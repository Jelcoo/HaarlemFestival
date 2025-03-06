<h2>Locations Management</h2>

<!-- Status message -->
<?php if (!empty($status['message'])): ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($status['message']); ?>
    </div>
<?php endif; ?>

<!-- Create New Location Button -->
<form action="/dashboard/locations" method="POST">
    <button type="submit" class="btn btn-primary mb-3" name="action" value="create">Create New Location</button>
</form>

<!-- Search Form -->
<form method="GET" action="/dashboard/locations" class="mb-3 d-flex align-items-center">
    <input type="text" name="search" placeholder="Search locations..."
        value="<?php echo htmlspecialchars($searchQuery); ?>" class="form-control d-inline-block w-auto me-2">

    <button type="submit" class="btn btn-primary me-2">Search</button>
    <?php if (!empty($searchQuery)) { ?>
        <a href="/dashboard/locations" class="btn btn-secondary text-white">Clear</a>
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
        <?php if (!empty($locations)) { ?>
            <?php foreach ($locations as $location) { ?>
                <tr>
                    <td><?php echo $location->id; ?></td>
                    <td><?php echo htmlspecialchars($location->name); ?></td>
                    <td><?php echo htmlspecialchars($location->coordinates); ?></td>
                    <td><?php echo htmlspecialchars($location->address); ?></td>
                    <td><?php echo htmlspecialchars($location->preview_description); ?></td>
                    <td><?php echo htmlspecialchars($location->main_description); ?></td>
                    <td class="d-flex align-items-center gap-2">
                        <!-- Edit Button - Redirect to Edit Form -->
                        <form action="/dashboard/locations" method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?php echo $location->id; ?>">
                            <button type="submit" class="btn btn-warning btn-sm" name="action" value="edit">Edit</button>
                        </form>


                        <!-- Delete Button -->
                        <form action="/dashboard/locations" method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?php echo $location->id; ?>">
                            <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="<?php echo count($columns); ?>">No locations found.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>