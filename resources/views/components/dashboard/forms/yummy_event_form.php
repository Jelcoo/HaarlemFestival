<?php
$isEdit = ($mode ?? 'create') === 'edit';
?>

<h2><?php echo $isEdit ? 'Update Yummy Event' : 'Create Yummy Event'; ?></h2>

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
        <form method="POST" action="/dashboard/events/yummy/<?php echo $isEdit ? 'edit' : 'create'; ?>">
            <?php if (!empty($formData['id'])) { ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($formData['id']); ?>">
            <?php } ?>

            <!-- Name and Total Seats -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="restaurant_id">Restaurant</label>
                    <select id="restaurant_id" name="restaurant_id" class="form-control" required>
                        <option value="" disabled selected>Select a restaurant</option>
                        <?php foreach ($restaurants as $restaurant) { ?>
                            <option value="<?php echo $restaurant->id; ?>"
                                <?php echo ($formData['restaurant_id'] ?? '') == $restaurant->id ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($restaurant->location->name ?? 'Unknown'); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="total_seats">Total Seats</label>
                    <input type="number" name="total_seats" class="form-control"
                        value="<?php echo htmlspecialchars($formData['total_seats'] ?? ''); ?>" required>
                </div>
            </div>

            <!-- Pricing and VAT -->
            <div class="row mt-3">
                <!-- Kids Price -->
                <div class="col-md-3">
                    <label for="kids_price">
                        Kids Price (€)
                        <?php if (!empty($formData['has_tickets'])): ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php endif; ?>
                    </label>
                    <input type="number" step="0.01" id="kids_price" name="kids_price" class="form-control"
                        value="<?php echo htmlspecialchars($formData['kids_price'] ?? ''); ?>" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>
                <div class="col-md-3">
                    <label for="kids_price_vat">
                        Kids Price (incl. VAT)
                        <?php if (!empty($formData['has_tickets'])): ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php endif; ?>
                    </label>
                    <input type="number" step="0.01" id="kids_price_vat" class="form-control" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>

                <!-- Adult Price -->
                <div class="col-md-3">
                    <label for="adult_price">
                        Adult Price (€)
                        <?php if (!empty($formData['has_tickets'])): ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php endif; ?>
                    </label>
                    <input type="number" step="0.01" id="adult_price" name="adult_price" class="form-control"
                        value="<?php echo htmlspecialchars($formData['adult_price'] ?? ''); ?>" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>
                <div class="col-md-3">
                    <label for="adult_price_vat">
                        Adult Price (incl. VAT)
                        <?php if (!empty($formData['has_tickets'])): ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php endif; ?>
                    </label>
                    <input type="number" step="0.01" id="adult_price_vat" class="form-control" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>
            </div>
            <div class="row mt-3">
                <!-- Reservation -->
                <div class="col-md-3">
                    <label for="reservation_cost">
                        Reservation Fee (€)
                        <?php if (!empty($formData['has_tickets'])): ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php endif; ?>
                    </label>
                    <input type="number" step="0.01" id="reservation_cost" name="reservation_cost" class="form-control"
                        value="<?php echo htmlspecialchars($formData['reservation_cost'] ?? ''); ?>" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>
                <div class="col-md-3">
                    <label for="reservation_cost_vat">
                        Reservation Fee (incl. VAT)
                        <?php if (!empty($formData['has_tickets'])): ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php endif; ?>
                    </label>
                    <input type="number" step="0.01" id="reservation_cost_vat" class="form-control" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>

                <!-- VAT -->
                <div class="col-md-6">
                    <label for="vat">
                        VAT (%)
                        <?php if (!empty($formData['has_tickets'])): ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php endif; ?>
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
                <a href="/dashboard/events/yummy" class="btn btn-outline-secondary">Cancel</a>
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
        { base: 'kids_price', incl: 'kids_price_vat' },
        { base: 'adult_price', incl: 'adult_price_vat' },
        { base: 'reservation_cost', incl: 'reservation_cost_vat' },
    ]
});
</script>
