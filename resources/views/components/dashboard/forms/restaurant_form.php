<h2><?php echo isset($formData['id']) ? 'Update Restaurant' : 'Create New Restaurant'; ?></h2>

<!-- Status message -->
<?php if (!empty($status['message'])) { ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($status['message']); ?>
    </div>
<?php } ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <form action="/dashboard/restaurants" method="POST">
                <input type="hidden" name="action"
                    value="<?php echo isset($formData['id']) ? 'update' : 'createNewRestaurant'; ?>">

                <?php if (isset($formData['id'])) { ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($formData['id']); ?>">
                <?php } ?>

                <!-- Name and Type -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="location_id">Location</label>
                            <select id="location_id" name="location_id" class="form-control" required>
                                <option value="">Select a location</option>
                                <?php foreach ($locations as $location) { ?>
                                    <option value="<?php echo $location->id; ?>"
                                        <?php echo (!empty($formData['location_id']) && $formData['location_id'] == $location->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($location->name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <!-- Rating -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rating">Rating (0-5)</label>
                            <input type="number" id="rating" name="rating" class="form-control" min="0" max="5" step="1"
                                value="<?php echo htmlspecialchars($formData['rating'] ?? '0'); ?>">
                        </div>
                    </div>
                </div>

                <!-- Restaurant Type -->
                <div class="form-group mt-3">
                    <div class="form-group">
                        <label for="restaurant_type">Restaurant Type</label>
                        <input type="text" id="restaurant_type" name="restaurant_type" class="form-control"
                            value="<?php echo htmlspecialchars($formData['restaurant_type'] ?? ''); ?>">
                    </div>
                </div>

                <!-- Menu -->
                <div class="form-group mt-3">
                    <label for="menu">Menu</label>
                    <textarea id="menu" name="menu"
                        class="form-control"><?php echo htmlspecialchars($formData['menu'] ?? ''); ?></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?php echo isset($formData['id']) ? '/dashboard/restaurants?details=' . $formData['id'] : '/dashboard/restaurants'; ?>"
                        class="btn btn-outline-secondary">Cancel</a>

                    <button type="submit" class="btn btn-primary">
                        <?php echo isset($formData['id']) ? 'Update' : 'Create'; ?> Restaurant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    initEditor('menu');
</script>
