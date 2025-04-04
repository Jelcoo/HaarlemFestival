<?php
$isEdit = ($mode ?? 'create') === 'edit';
?>

<h2><?php echo $isEdit ? 'Update History Event' : 'Create History Event'; ?></h2>

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
        <form method="POST" action="/dashboard/events/history/<?php echo $isEdit ? 'edit' : 'create'; ?>">
            <?php if (!empty($formData['id'])) { ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($formData['id']); ?>">
            <?php } ?>

            <!-- Language and Guide -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="language">Language</label>
                    <input type="text" name="language" class="form-control"
                        value="<?php echo htmlspecialchars($formData['language'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="guide">Guide</label>
                    <input type="text" name="guide" class="form-control"
                        value="<?php echo htmlspecialchars($formData['guide'] ?? ''); ?>" required>
                </div>
            </div>

            <!-- Seats and Start Location -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="seats_per_tour">
                        Seats Per Tour
                        <?php if (!empty($formData['has_tickets'])) { ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php } ?>
                    </label>
                    <input type="number" name="seats_per_tour" class="form-control"
                        value="<?php echo htmlspecialchars($formData['seats_per_tour'] ?? ''); ?>" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>
                <div class="col-md-6">
                    <label for="start_location">Start Location</label>
                    <input type="text" name="start_location" class="form-control"
                        value="<?php echo htmlspecialchars($formData['start_location'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="row mt-3">
                <!-- Single Price -->
                <div class="col-md-3">
                    <label for="single_price">
                        Single Price (€)
                        <?php if (!empty($formData['has_tickets'])) { ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php } ?>
                    </label>
                    <input type="number" step="0.01" id="single_price" name="single_price" class="form-control"
                        value="<?php echo htmlspecialchars($formData['single_price'] ?? ''); ?>" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>
                <div class="col-md-3">
                    <label for="single_price_vat">
                        Single Price (incl. VAT)
                        <?php if (!empty($formData['has_tickets'])) { ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php } ?>
                    </label>
                    <input type="number" step="0.01" id="single_price_vat" class="form-control" required
                    <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>

                <!-- Family Price -->
                <div class="col-md-3">
                    <label for="family_price">
                        Family Price (€)
                        <?php if (!empty($formData['has_tickets'])) { ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php } ?>
                    </label>
                    <input type="number" step="0.01" id="family_price" name="family_price" class="form-control"
                        value="<?php echo htmlspecialchars($formData['family_price'] ?? ''); ?>" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>
                <div class="col-md-3">
                    <label for="family_price_vat">
                        Family Price (incl. VAT)
                        <?php if (!empty($formData['has_tickets'])) { ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php } ?>
                    </label>
                    <input type="number" step="0.01" id="family_price_vat" class="form-control" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>
            </div>

            <!-- VAT input -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <label for="vat">
                        VAT (%)
                        <?php if (!empty($formData['has_tickets'])) { ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php } ?>
                    </label>
                    <input type="number" step="0.01" id="vat" name="vat" class="form-control"
                        value="<?php echo isset($formData['vat']) ? htmlspecialchars($formData['vat'] * 100) : ''; ?>" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>
            </div>

            <!-- Date and Time -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" class="form-control"
                        value="<?php echo htmlspecialchars($formData['start_date'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="start_time">Start Time</label>
                    <input type="time" name="start_time" class="form-control"
                        value="<?php echo htmlspecialchars($formData['start_time'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" class="form-control"
                        value="<?php echo htmlspecialchars($formData['end_date'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="end_time">End Time</label>
                    <input type="time" name="end_time" class="form-control"
                        value="<?php echo htmlspecialchars($formData['end_time'] ?? ''); ?>" required>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-between mt-4">
                <a href="/dashboard/events/history" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <?php echo $isEdit ? 'Update' : 'Create'; ?> Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
new VatPriceHelper({
    vatFieldId: 'vat',
    bindings: [
        { base: 'single_price', incl: 'single_price_vat' },
        { base: 'family_price', incl: 'family_price_vat' },
    ]
});
</script>
