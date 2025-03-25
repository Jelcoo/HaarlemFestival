<?php
$isEdit = ($mode ?? 'create') === 'edit';
?>

<h2><?php echo $isEdit ? 'Update History Event' : 'Create History Event'; ?></h2>

<?php if (!empty($errors)) { ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php } ?>

<div class="container-fluid">
    <div class="col-md-8">
        <form method="POST" action="/dashboard/events/history/<?php echo $isEdit ? 'edit' : 'create'; ?>">
            <?php if (!empty($formData['id'])): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($formData['id']) ?>">
            <?php endif; ?>

            <!-- Language and Guide -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="language">Language</label>
                    <input type="text" name="language" class="form-control"
                        value="<?= htmlspecialchars($formData['language'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="guide">Guide</label>
                    <input type="text" name="guide" class="form-control"
                        value="<?= htmlspecialchars($formData['guide'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Seats and Start Location -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="seats_per_tour">Seats Per Tour</label>
                    <input type="number" name="seats_per_tour" class="form-control"
                        value="<?= htmlspecialchars($formData['seats_per_tour'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="start_location">Start Location</label>
                    <input type="text" name="start_location" class="form-control"
                        value="<?= htmlspecialchars($formData['start_location'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Prices and VAT -->
            <div class="row mt-3">
                <div class="col-md-4">
                    <label for="single_price">Single Price</label>
                    <input type="number" step="0.01" name="single_price" class="form-control"
                        value="<?= htmlspecialchars($formData['single_price'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="family_price">Family Price</label>
                    <input type="number" step="0.01" name="family_price" class="form-control"
                        value="<?= htmlspecialchars($formData['family_price'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="vat">VAT (decimal)</label>
                    <input type="number" step="0.01" name="vat" class="form-control"
                        value="<?= htmlspecialchars($formData['vat'] ?? '0.21') ?>" required>
                </div>
            </div>

            <!-- Date and Time -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" class="form-control"
                        value="<?= htmlspecialchars($formData['start_date'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="start_time">Start Time</label>
                    <input type="time" name="start_time" class="form-control"
                        value="<?= htmlspecialchars($formData['start_time'] ?? '') ?>" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" class="form-control"
                        value="<?= htmlspecialchars($formData['end_date'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="end_time">End Time</label>
                    <input type="time" name="end_time" class="form-control"
                        value="<?= htmlspecialchars($formData['end_time'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-between mt-4">
                <a href="/dashboard/events/history" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Update' : 'Create' ?> Event
                </button>
            </div>
        </form>
    </div>
</div>
