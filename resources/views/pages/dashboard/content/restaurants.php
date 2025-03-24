<!-- Title and Create Button -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Restaurant Management</h2>
    <form action="/dashboard/restaurants" method="POST">
        <button type="submit" class="btn btn-success" name="action" value="export">Export to CSV</button>
        <a href="/dashboard/restaurants/create" class="btn btn-primary">Create New Restaurant</a>
    </form>
</div>

<!-- Status Message -->
<?php if (!empty($status['message'])) { ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo $status['message']; ?>
    </div>
<?php } ?>

<!-- Sort -->
<form method="GET" action="/dashboard/restaurants" class="mb-3 d-flex justify-content-between align-items-center">
    <!-- Search -->
    <div class="d-flex align-items-center gap-2">
        <input type="text" name="search" placeholder="Search restaurants..."
            value="<?php echo htmlspecialchars($searchQuery); ?>" class="form-control d-inline-block w-auto"
            style="max-width: 200px;">
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if (!empty($searchQuery)) { ?>
            <a href="/dashboard/restaurants" class="btn btn-secondary text-white">Clear</a>
        <?php } ?>
    </div>

    <!-- Sort -->
    <div class="d-flex align-items-center gap-2">
        <select name="sort" id="sortSelect" class="form-select" style="width: 150px;">
            <option value="" disabled selected>Sort by...</option>
            <option value="restaurant_type" <?php echo ($sortColumn == 'restaurant_type') ? 'selected' : ''; ?>>Type
            </option>
            <option value="rating" <?php echo ($sortColumn == 'rating') ? 'selected' : ''; ?>>Rating</option>
            <option value="address" <?php echo ($sortColumn == 'address') ? 'selected' : ''; ?>>Address</option>
        </select>

        <select name="direction" id="directionSelect" class="form-select" style="width: 150px;">
            <option value="asc" <?php echo ($sortDirection == 'asc') ? 'selected' : ''; ?>>Ascending</option>
            <option value="desc" <?php echo ($sortDirection == 'desc') ? 'selected' : ''; ?>>Descending</option>
        </select>

        <button type="button" class="btn btn-primary" onclick="updateURL()">Apply</button>
        <button type="button" class="btn btn-secondary" onclick="resetSort()">Reset</button>
    </div>
</form>

<div class="row">
    <?php if (!empty($restaurants)) { ?>
        <?php foreach ($restaurants as $restaurant) { ?>
            <?php include __DIR__ . '/../../../components/dashboard/restaurant_card.php'; ?>
        <?php } ?>
    <?php } else { ?>
        <p>No restaurants found.</p>
    <?php } ?>
</div>

<script src="/assets/js/utils.js"></script>