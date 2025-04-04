<?php
$isEdit = ($mode ?? 'create') === 'edit';
?>

<h2><?php echo isset($formData['id']) ? 'Update Dance Event' : 'Create Dance Event'; ?></h2>

<!-- Status message -->
<?php if (!empty($status['message'])) { ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($status['message']); ?>
    </div>
<?php } ?>

<div class="container-fluid">
    <div class="col-md-8">
        <form action="/dashboard/events/dance/<?php echo $isEdit ? 'edit' : 'create'; ?>" method="POST" enctype="multipart/form-data">
            <?php if (isset($formData['id'])) { ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($formData['id']); ?>">
            <?php } ?>

            <!-- Location -->
            <div class="form-group mt-3">
                <label for="location_id">Location</label>
                <select name="location_id" id="location_id" class="form-control" required>
                    <option value="" disabled selected>Select a location</option>
                    <?php foreach ($locations as $location) { ?>
                        <option value="<?php echo $location->id; ?>"
                            <?php echo (isset($formData['location_id']) && $formData['location_id'] == $location->id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($location->name); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Date and Time -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control"
                        value="<?php echo htmlspecialchars($formData['start_date'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="start_time">Start Time</label>
                    <input type="time" id="start_time" name="start_time" class="form-control"
                        value="<?php echo htmlspecialchars($formData['start_time'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control"
                        value="<?php echo htmlspecialchars($formData['end_date'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="end_time">End Time</label>
                    <input type="time" id="end_time" name="end_time" class="form-control"
                        value="<?php echo htmlspecialchars($formData['end_time'] ?? ''); ?>" required>
                </div>
            </div>

            <!-- Session -->
            <div class="form-group mt-3">
                <label for="session">Session</label>
                <select id="session" name="session" class="form-control" required>
                    <option value="" disabled selected>Select a session</option>
                    <?php foreach ($sessions as $session) { ?>
                        <option value="<?php echo $session->value; ?>"
                            <?php echo (isset($formData['session']) && $formData['session'] === $session->value) ? 'selected' : ''; ?>>
                            <?php echo ucfirst(str_replace('_', ' ', $session->name)); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Tickets, Price, VAT -->
            <div class="row mt-3">
                <div class="col-md-3">
                    <label for="total_tickets">
                        Total Tickets 
                        <?php if (!empty($formData['has_tickets'])) { ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php } ?>
                    </label>
                    <input type="number" id="total_tickets" name="total_tickets" class="form-control"
                        value="<?php echo htmlspecialchars($formData['total_tickets'] ?? ''); ?>" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>
                <div class="col-md-3">
                <label for="price">
                        Base Price (€) 
                        <?php if (!empty($formData['has_tickets'])) { ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php } ?>
                    </label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01"
                        value="<?php echo htmlspecialchars($formData['price'] ?? ''); ?>" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>
                <div class="col-md-3">
                    <label for="total_price">
                        Total Price (€)
                        <?php if (!empty($formData['has_tickets'])) { ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php } ?>
                    </label>
                    <input type="number" id="total_price" class="form-control" step="0.01" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>
                <div class="col-md-3">
                    <label for="vat">
                        VAT (%) 
                        <?php if (!empty($formData['has_tickets'])) { ?>
                            <i class="fas fa-lock" title="Locked - tickets have been sold"></i>
                        <?php } ?>
                    </label>
                    <input type="number" id="vat" name="vat" class="form-control" step="0.01"
                        value="<?php echo htmlspecialchars($formData['vat'] * 100 ?? ''); ?>" required
                        <?php echo !empty($formData['has_tickets']) ? 'readonly' : ''; ?>>
                </div>
            </div>

            <!-- Artist Selector -->
            <div class="form-group mt-3">
                <label for="artist_selector">Add Artist</label>
                <select id="artist_selector" class="form-control">
                    <option disabled selected>Select an artist</option>
                    <?php foreach ($artists as $artist) { ?>
                        <option value="<?php echo $artist->id; ?>"><?php echo htmlspecialchars($artist->name); ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Selected Artists -->
            <div class="form-group mt-3">
                <label>Selected Artists</label>
                <ul id="selected-artists" class="list-group mb-2">
                    <?php foreach ($formData['selected_artists'] ?? [] as $artist) { ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($artist['name']); ?>
                            <button type="button" class="btn btn-sm btn-danger remove-artist" data-id="<?php echo $artist['id']; ?>">Remove</button>
                            <input type="hidden" name="artist_ids[]" value="<?php echo $artist['id']; ?>">
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="/dashboard/events/dance" class="btn btn-outline-secondary">Cancel</a>

                <button type="submit" class="btn btn-primary">
                    <?php echo $isEdit ? 'Update' : 'Create'; ?> Dance Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const selector = document.getElementById('artist_selector');
    const list = document.getElementById('selected-artists');

    // Disable already selected artists
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input[name="artist_ids[]"]').forEach(input => {
            const option = selector.querySelector(`option[value="${input.value}"]`);
            if (option) option.disabled = true;
        });
    });

    // Add artist to the list
    selector.addEventListener('change', function () {
        const id = this.value;
        const name = this.options[this.selectedIndex].text;
        if (!id) return;

        // Disable selected artist in dropdown
        this.options[this.selectedIndex].disabled = true;

        // Create list item with remove button and hidden input
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `
            ${name}
    <button type="button" class="btn btn-sm btn-danger remove-artist" data-id="${id}">Remove</button>
    <input type="hidden" name="artist_ids[]" value="${id}">
`;
        list.appendChild(li);
        this.selectedIndex = 0;
    });

    // Remove artist from the list
    document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('remove-artist')) return;

        const id = e.target.dataset.id;
        const option = [...selector.options].find(opt => opt.value === id);
        if (option) option.disabled = false;

        e.target.closest('li').remove();
    });

    new VatPriceHelper({
        vatFieldId: 'vat',
        bindings: [
            { base: 'price', incl: 'total_price' },
        ]
    });
</script>
