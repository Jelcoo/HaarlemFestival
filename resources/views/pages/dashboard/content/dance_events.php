<!-- Title and Create Button -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Dance Event Management</h2>
    <div>
        <a href="/dashboard/events/dance/export" class="btn btn-success">Export to CSV</a>
        <a href="/dashboard/events/dance/create" class="btn btn-primary">Create New Dance Event</a>
    </div>
</div>

<!-- Status Message -->
<?php if (!empty($status['message'])) { ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($status['message']); ?>
    </div>
<?php } ?>

<!-- Search & Sort -->
<form method="GET" action="/dashboard/events/dance" class="mb-3 d-flex justify-content-between align-items-center">
    <!-- Search -->
    <div class="d-flex align-items-center gap-2">
        <input type="text" name="search" placeholder="Search events..."
            value="<?php echo htmlspecialchars($searchQuery); ?>" class="form-control d-inline-block w-auto"
            style="max-width: 200px;">
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if (!empty($searchQuery)) { ?>
            <a href="/dashboard/dance-events" class="btn btn-secondary text-white">Clear</a>
        <?php } ?>
    </div>

    <!-- Sort -->
    <div class="d-flex align-items-center gap-2">
        <select name="sort" class="form-select" style="width: 150px;">
            <option value="" disabled selected>Sort by...</option>
            <option value="start_date" <?php echo ($sortColumn == 'start_date') ? 'selected' : ''; ?>>Start Date</option>
            <option value="location_name" <?php echo ($sortColumn == 'location_name') ? 'selected' : ''; ?>>Location</option>
            <option value="artist_names" <?php echo ($sortColumn == 'artist_names') ? 'selected' : ''; ?>>Artists</option>
        </select>

        <select name="direction" class="form-select" style="width: 150px;">
            <option value="asc" <?php echo ($sortDirection == 'asc') ? 'selected' : ''; ?>>Ascending</option>
            <option value="desc" <?php echo ($sortDirection == 'desc') ? 'selected' : ''; ?>>Descending</option>
        </select>

        <button type="submit" class="btn btn-primary">Apply</button>
        <a href="/dashboard/dance-events" class="btn btn-secondary">Reset</a>
    </div>
</form>

<!-- Event Cards -->

<?php if (!isset($events) || !is_array($events)) return; ?>
<div class="row">
    <?php foreach ($events as $event): ?>
        <?php include __DIR__ . '/../../../components/dashboard/dance_event_card.php'; ?>
    <?php endforeach; ?>
</div>
