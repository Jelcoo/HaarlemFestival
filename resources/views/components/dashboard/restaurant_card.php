<?php
if (!isset($restaurant)) {
    return;
}
?>

<div class="col-md-4">
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">
                <?php echo isset($restaurant->location->name) ? htmlspecialchars($restaurant->location->name) : 'Unknown Restaurant'; ?>
            </h5>

            <!-- Type -->
            <p class="card-text"><strong>Type:</strong>
                <?php echo htmlspecialchars($restaurant->restaurant_type ?? 'Unknown Type'); ?>
            </p>
            <!-- Rating -->
            <p class="card-text"><strong>Rating:</strong>
                <?php echo isset($restaurant->rating) && is_numeric($restaurant->rating)
                    ? htmlspecialchars($restaurant->rating) . '/5'
                    : 'No rating available'; ?>
            </p>
            <!-- Address -->
            <p><strong>Address:</strong>
                <?php echo isset($restaurant->location->address) ? htmlspecialchars($restaurant->location->address ?? 'No Address Provided') : 'No Address Available'; ?>
            </p>
            <!-- Menu -->
            <p><strong>Menu:</strong>
                <?php echo (!empty(trim($restaurant->menu)) ? htmlspecialchars($restaurant->menu) : 'Empty'); ?>
            </p>

            <!-- Actions -->
            <div class="d-flex justify-content-end mt-3">
                <form action="/dashboard/restaurants" method="POST" class="d-inline">
                    <input type="hidden" name="id" value="<?php echo $restaurant->id; ?>">
                    <button type="submit" class="btn btn-warning btn-sm" name="action" value="edit">Edit</button>
                    <button type="submit" class="btn btn-danger btn-sm ms-2" name="action"
                        value="delete">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>