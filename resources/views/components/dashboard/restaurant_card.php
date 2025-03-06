<?php if (!isset($restaurant)) {
    return;
}
$showDetails = isset($_GET['details']) && $_GET['details'] == $restaurant->id;
$showEditForm = isset($_GET['edit']) && $_GET['edit'] == $restaurant->id;

$locationMatch = array_filter($locations, fn($loc) => $loc->id == $restaurant->location_id);
$location = !empty($locationMatch) ? reset($locationMatch) : [
    'name' => 'Unknown Location',
    'address' => 'Unknown
    Address'
];
?>

<div class="col-md-4">
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($restaurant->name); ?></h5>

            <?php if (!$showEditForm) { ?>
                <!-- Type and Rating and location -->
                <p class="card-text"><strong>Type:</strong>
                    <?php echo htmlspecialchars($restaurant->restaurant_type); ?>
                </p>
                <p class="card-text"><strong>Rating:</strong> <?php echo htmlspecialchars($restaurant->rating); ?>/5</p>
                <p><strong>Location:</strong>
                    <?php echo htmlspecialchars($location->name) ?> </br>
                    <?php echo htmlspecialchars($location->address); ?>
                </p>
            <?php } ?>

            <?php if (!$showDetails) { ?>
                <!-- Default info -->
                <a href="/dashboard/restaurants?details=<?php echo $restaurant->id; ?>" class="btn btn-info btn-sm">More
                    Info</a>
                <form action="/dashboard/restaurants" method="POST" class="d-inline">
                    <input type="hidden" name="id" value="<?php echo $restaurant->id; ?>">
                    <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete">Delete</button>
                </form>
            <?php } else { ?>
                <div class="mt-3">
                    <?php if (!$showEditForm) { ?>
                        <!-- Detail info -->
                        <p><strong>Preview:</strong> <?php echo htmlspecialchars($restaurant->preview_description); ?></p>
                        <p><strong>Main Description:</strong> <?php echo htmlspecialchars($restaurant->main_description); ?>
                        </p>
                        <p><strong>Menu:</strong> <?php echo htmlspecialchars($restaurant->menu); ?></p>

                        <!-- Less Info, Edit, and Delete buttons -->
                        <div class="mt-3">
                            <a href="/dashboard/restaurants" class="btn btn-secondary btn-sm">Less Info</a>
                            <a href="/dashboard/restaurants?details=<?php echo $restaurant->id; ?>&edit=<?php echo $restaurant->id; ?>"
                                class="btn btn-warning btn-sm">Edit</a>
                            <form action="/dashboard/restaurants" method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $restaurant->id; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete">Delete</button>
                            </form>
                        </div>
                    <?php } else { ?>
                        <!-- Editabl Form -->
                        <form
                            action="/dashboard/restaurants?details=<?php echo $restaurant->id; ?>&edit=<?php echo $restaurant->id; ?>"
                            method="POST">
                            <input type="hidden" name="id" value="<?php echo $restaurant->id; ?>">

                            <div class="mb-2">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="<?php echo htmlspecialchars($restaurant->name); ?>" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Type</label>
                                <input type="text" name="restaurant_type" class="form-control"
                                    value="<?php echo htmlspecialchars($restaurant->restaurant_type); ?>">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Rating</label>
                                <input type="number" name="rating" class="form-control"
                                    value="<?php echo htmlspecialchars($restaurant->rating); ?>" min="0" max="5">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Location</label>
                                <select name="location_id" class="form-control" required>
                                    <option value="">Select a location</option>
                                    <?php foreach ($locations as $loc) { ?>
                                        <option value="<?php echo $loc->id; ?>" <?php echo ($restaurant->location_id == $loc->id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($loc->name) . ' - ' . htmlspecialchars($loc->address); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Preview Description</label>
                                <textarea name="preview_description"
                                    class="form-control"><?php echo htmlspecialchars($restaurant->preview_description); ?></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Main Description</label>
                                <textarea name="main_description"
                                    class="form-control"><?php echo htmlspecialchars($restaurant->main_description); ?></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Menu</label>
                                <textarea name="menu"
                                    class="form-control"><?php echo htmlspecialchars($restaurant->menu); ?></textarea>
                            </div>

                            <!-- Save and Cancel -->
                            <button type="submit" class="btn btn-success btn-sm" name="action" value="update">Save
                                Changes</button>
                            <a href="/dashboard/restaurants?details=<?php echo $restaurant->id; ?>"
                                class="btn btn-secondary btn-sm">Cancel</a>
                        </form>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>