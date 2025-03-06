<h2><?php echo isset($formData['id']) ? 'Update Location' : 'Create Location' ?></h2>

<!-- Status message -->
<?php if (!empty($status['message'])): ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($status['message']); ?>
    </div>
<?php endif; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <form action="/dashboard/locations" method="POST">
                <input type="hidden" name="action"
                    value="<?php echo isset($formData['id']) ? 'update' : 'createLocation'; ?>">

                <?php if (isset($formData['id'])): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($formData['id']); ?>">
                <?php endif; ?>

                <!-- Location Name -->
                <div class="form-group">
                    <label for="name">Location Name</label>
                    <input type="text" id="name" name="name" class="form-control" required
                        value="<?php echo htmlspecialchars($formData['name'] ?? ''); ?>">
                </div>

                <!-- Address -->
                <div class="form-group mt-3">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" class="form-control" required
                        value="<?php echo htmlspecialchars($formData['address'] ?? ''); ?>">
                </div>

                <!-- Coordinates -->
                <div class="form-group mt-3">
                    <label for="coordinates">Coordinates (Latitude, Longitude)</label>
                    <input type="text" id="coordinates" name="coordinates" class="form-control"
                        placeholder="e.g., 52.3676, 4.9041"
                        value="<?php echo htmlspecialchars($formData['coordinates'] ?? ''); ?>">
                </div>

                <!-- Preview Description -->
                <div class="form-group mt-3">
                    <label for="preview_description">Preview Description</label>
                    <textarea id="preview_description" name="preview_description"
                        class="form-control"><?php echo htmlspecialchars($formData['preview_description'] ?? ''); ?></textarea>
                </div>

                <!-- Main Description -->
                <div class="form-group mt-3">
                    <label for="main_description">Main Description</label>
                    <textarea id="main_description" name="main_description"
                        class="form-control"><?php echo htmlspecialchars($formData['main_description'] ?? ''); ?></textarea>
                </div>

                <button type="submit"
                    class="btn btn-primary mt-4"><?php echo isset($formData['id']) ? 'Update Location' : 'Create Location' ?></button>
            </form>
        </div>
    </div>
</div>