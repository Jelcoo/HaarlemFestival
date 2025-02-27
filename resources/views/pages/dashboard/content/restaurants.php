<h2>Restaurant Management</h2>

<!-- Status message -->
<?php if (!empty($status['message'])) { ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo $status['message']; ?>
    </div>
<?php } ?>

<!-- Create Button -->
<form action="/dashboard/restaurants" method="POST">
    <button type="submit" class="btn btn-primary mb-3" name="action" value="create">Create New Restaurant</button>
</form>

<!-- Search Form -->
<form method="GET" action="/dashboard/restaurants" class="mb-3 d-flex align-items-center">
    <input type="text" name="search" placeholder="Search restaurants..."
        value="<?php echo htmlspecialchars($searchQuery); ?>" class="form-control d-inline-block w-auto me-2">

    <button type="submit" class="btn btn-primary me-2">Search</button>
    <?php if (!empty($searchQuery)) { ?>
        <a href="/dashboard/restaurants" class="btn btn-secondary text-white">Clear</a>
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
        <?php if (!empty($restaurants)) { ?>
            <?php foreach ($restaurants as $restaurant) { ?>
                <tr id="restaurant-row-<?php echo $restaurant->id; ?>">
                    <form action="/dashboard/restaurants" method="POST">
                        <input type="hidden" name="id" value="<?php echo $restaurant->id; ?>">

                        <td><?php echo $restaurant->id; ?></td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $restaurant->id) { ?>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($restaurant->name); ?>"
                                    class="form-control w-100" required>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($restaurant->name); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $restaurant->id) { ?>
                                <input type="text" name="restaurant_type"
                                    value="<?php echo htmlspecialchars($restaurant->restaurant_type); ?>" class="form-control w-100"
                                    required>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($restaurant->restaurant_type); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $restaurant->id) { ?>
                                <input type="number" name="rating" value="<?php echo htmlspecialchars($restaurant->rating); ?>"
                                    class="form-control w-100" required min="0" max="5">
                            <?php } else { ?>
                                <?php echo htmlspecialchars($restaurant->rating); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $restaurant->id) { ?>
                                <input type="text" name="location" value="<?php echo htmlspecialchars($restaurant->location_id); ?>"
                                    class="form-control w-100" required>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($restaurant->location_id); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $restaurant->id) { ?>
                                <textarea name="preview_description"
                                    class="form-control w-100"><?php echo htmlspecialchars($restaurant->preview_description); ?></textarea>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($restaurant->preview_description); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $restaurant->id) { ?>
                                <textarea name="main_description"
                                    class="form-control w-100"><?php echo htmlspecialchars($restaurant->main_description); ?></textarea>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($restaurant->main_description); ?>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $restaurant->id) { ?>
                                <textarea name="menu"
                                    class="form-control w-100"><?php echo htmlspecialchars($restaurant->menu); ?></textarea>
                            <?php } else { ?>
                                <?php echo htmlspecialchars($restaurant->menu); ?>
                            <?php } ?>
                        </td>

                        <td class="d-flex align-items-center gap-2">
                            <!-- Edit/Update Button -->
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == $restaurant->id) { ?>
                                <button type="submit" class="btn btn-success btn-sm" name="action" value="update">Update</button>
                            <?php } else { ?>
                                <a href="/dashboard/restaurants?edit=<?php echo $restaurant->id; ?>"
                                    class="btn btn-warning btn-sm">Edit</a>
                            <?php } ?>

                            <!--- Delete Button -->
                            <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete">Delete</button>
                        </td>
                    </form>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="<?php echo count($columns); ?>">No restaurants found.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>