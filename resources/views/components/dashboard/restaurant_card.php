<?php
if (!isset($restaurant)) {
    return;
}
?>

<div class="col-md-4">
    <div class="card mb-4">
        <div class="card-body d-flex align-items-start">
            <!-- Restaurant Logo -->
            <img src="<?php echo $restaurant->logo; ?>" alt="Restaurant Image" class="img-fluid restaurant-logo me-3">

            <!-- Restaurant Details -->
            <div class="w-100">
                <h5 class="card-title">
                    <?php echo isset($restaurant->location->name) ? htmlspecialchars($restaurant->location->name) : 'Unknown Restaurant'; ?>
                </h5>

                <!-- Type -->
                <p class="card-text"><strong>Type:</strong>
                    <?php echo !empty(trim($restaurant->restaurant_type)) ? strip_tags($restaurant->restaurant_type) : 'Empty'; ?>
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
                    <?php echo !empty(trim($restaurant->menu)) ? strip_tags($restaurant->menu) : 'Empty'; ?>
                </p>

                <!-- Actions -->
                <div class="d-flex gap-2 justify-content-end mt-3">
                    <!-- Edit -->
                    <a href="/dashboard/restaurants/edit?id=<?= $restaurant->id ?>" class="btn btn-warning btn-sm">Edit</a>

                    <!-- Delete -->
                    <form action="/dashboard/restaurants/delete" method="POST" class="d-inline">
                        <input type="hidden" name="id" value="<?php echo $restaurant->id; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .restaurant-logo {
        width: 8rem;
        height: auto;
    }
</style>
