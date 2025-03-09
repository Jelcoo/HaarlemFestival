<h2>Locations Management</h2>

<!-- Status message -->
<?php if (!empty($status['message'])): ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($status['message']); ?>
    </div>
<?php endif; ?>

<!-- Create New Location Button -->
<form action="/dashboard/locations" method="POST">
    <button type="submit" class="btn btn-primary mb-3" name="action" value="create">Create New Location</button>
</form>

<!-- Search Form -->
<form method="GET" action="/dashboard/locations" class="mb-3 d-flex align-items-center">
    <input type="text" name="search" placeholder="Search locations..."
        value="<?php echo htmlspecialchars($searchQuery); ?>" class="form-control d-inline-block w-auto me-2">
    <button type="submit" class="btn btn-primary me-2">Search</button>
    <?php if (!empty($searchQuery)) { ?>
        <a href="/dashboard/locations" class="btn btn-secondary text-white">Clear</a>
    <?php } ?>
</form>

<div class="row">
    <?php if (!empty($locations)) { ?>
        <?php foreach ($locations as $location) { ?>
            <?php include __DIR__ . '/../../../components/dashboard/location_card.php'; ?>
        <?php } ?>
    <?php } else { ?>
        <p>No locations found.</p>
    <?php } ?>
</div>