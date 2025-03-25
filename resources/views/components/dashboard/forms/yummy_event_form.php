<?php
$isEdit = ($mode ?? 'create') === 'edit';
?>

<h2><?php echo $isEdit ? 'Update Yummy Event' : 'Create Yummy Event'; ?></h2>

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
        <form method="POST" action="/dashboard/events/yummy/<?php echo $isEdit ? 'edit' : 'create'; ?>">
            <?php if (!empty($formData['id'])): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($formData['id']) ?>">
            <?php endif; ?>

            <!-- Name and Total Seats -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="restaurant_id">Restaurant</label>
                    <select id="restaurant_id" name="restaurant_id" class="form-control" required>
                        <option value="" disabled selected>Select a restaurant</option>
                        <?php foreach ($restaurants as $restaurant): ?>
                            <option value="<?= $restaurant->id ?>"
                                <?= ($formData['restaurant_id'] ?? '') == $restaurant->id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($restaurant->location->name ?? 'Unknown') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="total_seats">Total Seats</label>
                    <input type="number" name="total_seats" class="form-control"
                        value="<?= htmlspecialchars($formData['total_seats'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Pricing and VAT -->
            <div class="row mt-3">
                <div class="col-md-3">
                    <label for="kids_price">Kids Price</label>
                    <input type="number" step="0.01" name="kids_price" class="form-control"
                        value="<?= htmlspecialchars($formData['kids_price'] ?? '') ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="adult_price">Adult Price</label>
                    <input type="number" step="0.01" name="adult_price" class="form-control"
                        value="<?= htmlspecialchars($formData['adult_price'] ?? '') ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="reservation_cost">Reservation Fee</label>
                    <input type="number" step="0.01" name="reservation_cost" class="form-control"
                        value="<?= htmlspecialchars($formData['reservation_cost'] ?? '') ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="vat">VAT (%)</label>
                    <input type="number" step="0.01" name="vat" class="form-control"
                        value="<?= htmlspecialchars($formData['vat'] ?? '0.21') * 100 ?>" required>
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
                <a href="/dashboard/events/yummy" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Update' : 'Create' ?> Event
                </button>
            </div>
        </form>
    </div>
</div>
