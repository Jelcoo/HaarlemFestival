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
                <tr id="location-row-<?php echo $location->id; ?>">
                    <form action="/dashboard/locations" method="POST">
                        <input type="hidden" name="id" value="<?php echo $location->id; ?>">

                        <td><?php echo $location->id; ?></td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $location->id) { ?>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($location->name); ?>"
                                    class="form-control w-100" required>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($location->name); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $location->id) { ?>
                                <input type="text" name="coordinates"
                                    value="<?php echo htmlspecialchars($location->coordinates); ?>" class="form-control w-100">
                            <?php } else { ?>
                                <?php echo htmlspecialchars($location->coordinates); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $location->id) { ?>
                                <input type="text" name="address" value="<?php echo htmlspecialchars($location->address); ?>"
                                    class="form-control w-100">
                            <?php } else { ?>
                                <?php echo htmlspecialchars($location->address); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $location->id) { ?>
                                <textarea name="preview_description"
                                    class="form-control w-100"><?php echo htmlspecialchars($location->preview_description); ?></textarea>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($location->preview_description); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $location->id) { ?>
                                <textarea name="main_description"
                                    class="form-control w-100"><?php echo htmlspecialchars($location->main_description); ?></textarea>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($location->main_description); ?>
                            <?php } ?>
                        </td>

                        <td class="d-flex align-items-center gap-2">
                            <!-- Edit/Update Button -->
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $location->id) { ?>
                                <button type="submit" class="btn btn-success btn-sm" name="action" value="update">Update</button>
                            <?php } else { ?>
                                <a href="/dashboard/locations?edit=<?php echo $location->id; ?>"
                                    class="btn btn-warning btn-sm">Edit</a>
                            <?php } ?>

                            <!-- Delete Button -->
                            <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete">Delete</button>
                        </td>
                    </form>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="<?php echo count($columns); ?>">No locations found.</td>
            </tr>
        <?php } ?>
    </tbody>


</table>