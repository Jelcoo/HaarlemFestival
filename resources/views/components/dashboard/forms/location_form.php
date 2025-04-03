<?php

use App\Enum\EventTypeEnum;

$isEdit = ($mode ?? 'create') === 'edit';

?>

<h2><?php echo isset($formData['id']) ? 'Update Location' : 'Create Location'; ?></h2>

<!-- Validation Errors -->
<?php if (!empty($errors)) { ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error) { ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>

<div class="container-fluid">
    <div class="col-md-8">
        <form action="/dashboard/locations/<?php echo $isEdit ? 'edit' : 'create'; ?>" method="POST" enctype="multipart/form-data">
            <?php if (isset($formData['id'])) { ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($formData['id']); ?>">
            <?php } ?>

            <!-- Location Cover -->
            <div class="form-group mt-3">
                <div class="form-group">
                    <label for="location_cover">Location Cover</label>
                    <div class="d-flex align-items-center">
                        <input type="file" id="location_cover" name="location_cover" class="form-control" accept="image/jpeg, image/png">
                        <button type="button" class="btn btn-danger ms-2" onclick="clearCoverInput()">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Location Name -->
            <div class="form-group mt-3">
                <label for="name">Location Name</label>
                <input type="text" id="name" name="name" class="form-control" required
                    value="<?php echo htmlspecialchars($formData['name'] ?? ''); ?>">
            </div>

            <!-- Event Type Dropdown -->
            <div class="form-group mt-3">
                <label for="event_type">Event Type</label>
                <select id="event_type" name="event_type" class="form-control" required>
                    <option value="" disabled selected>Select an event type</option>
                    <?php foreach (EventTypeEnum::cases() as $eventType) { ?>
                        <option value="<?php echo $eventType->value; ?>"
                            <?php echo (isset($formData['event_type']) && $formData['event_type'] === $eventType->value) ? 'selected' : ''; ?>>
                            <?php echo ucfirst($eventType->name); ?>
                        </option>
                    <?php } ?>
                </select>
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

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="<?php echo isset($formData['id']) ? '/dashboard/locations?details=' . $formData['id'] : '/dashboard/locations'; ?>"
                    class="btn btn-outline-secondary">Cancel</a>

                <button type="submit" class="btn btn-primary">
                    <?php echo isset($formData['id']) ? 'Update' : 'Create'; ?> Location
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const coverInput = document.getElementById('location_cover');
    <?php if (!empty($formData['cover'])) { ?>
        fillFileInput(coverInput, '<?php echo $formData['cover']; ?>');
    <?php } ?>

    initEditor('preview_description');
    initEditor('main_description');

    function clearCoverInput() {
        document.getElementById('location_cover').value = "";
    }
</script>
