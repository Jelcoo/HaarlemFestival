<h2>Restaurant Management</h2>

<!-- Status Message -->
<?php if (!empty($status['message'])) { ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo $status['message']; ?>
    </div>
<?php } ?>

<!-- Create New Restaurant Button -->
<form action="/dashboard/restaurants" method="POST">
    <button type="submit" class="btn btn-primary mb-3" name="action" value="create">Create New Restaurant</button>
</form>

<!-- Search Form -->
<form method="GET" action="/dashboard/restaurants" class="mb-3 d-flex align-items-center">
    <input type="text" name="search" placeholder="Search restaurants..."
        value="<?php echo htmlspecialchars($searchQuery); ?>" class="form-control d-inline-block w-auto me-2">
    <button type="submit" class="btn btn-primary me-2">Search</button>
    <?php if (!empty($searchQuery)) { ?>
        <a href="/dashboard/restaurants" class="btn btn-secondary text-white">Clear</a>
    <?php } ?>
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