<h2>Create New Restaurant</h2>

<!-- Status message -->
<?php if (!empty($status['message'])): ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($status['message']); ?>
    </div>
<?php endif; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <form action="/dashboard/restaurants" method="POST">
                <input type="hidden" name="action" value="createNewRestaurant">

                <!-- Name and Type -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Restaurant Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="restaurant_type">Restaurant Type</label>
                            <input type="text" id="restaurant_type" name="restaurant_type" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Rating and Location -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rating">Rating (0-5)</label>
                            <input type="number" id="rating" name="rating" class="form-control" min="0" max="5"
                                step="0.5" value="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="location_id">Location</label>
                            <select id="location_id" name="location_id" class="form-control" required>
                                <option value="">Select a location</option>
                                <?php foreach ($locations as $location) { ?>
                                    <option value="<?php echo $location->id; ?>">
                                        <?php echo htmlspecialchars($location->name); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="form-group mt-3">
                    <label for="preview_description">Preview Description</label>
                    <textarea id="preview_description" name="preview_description" class="form-control"></textarea>
                </div>

                <!-- Main Description -->
                <div class="form-group mt-3">
                    <label for="main_description">Main Description</label>
                    <textarea id="main_description" name="main_description" class="form-control"></textarea>
                </div>

                <!-- Menu -->
                <div class="form-group mt-3">
                    <label for="menu">Menu</label>
                    <textarea id="menu" name="menu" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Create Restaurant</button>
            </form>
        </div>
    </div>
</div>