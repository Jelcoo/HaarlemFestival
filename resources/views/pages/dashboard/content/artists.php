<!-- Title and Create Button -->
<div class="d-block d-md-flex justify-content-between align-items-center mb-3">
    <h2>Artist Management</h2>
    <div class="d-flex gap-2">
        <a href="/dashboard/artists/export" class="btn btn-success">Export to CSV</a>
        <a href="/dashboard/artists/create" class="btn btn-primary">Create New Artist</a>
    </div>
</div>

<!-- Status message -->
<?php if (!empty($status['message'])) { ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($status['message']); ?>
    </div>
<?php } ?>

<!-- Sort -->
<form method="GET" action="/dashboard/artists" class="mb-3 d-block d-md-flex justify-content-between align-items-center">
    <!-- Search -->
    <div class="d-flex align-items-center gap-2">
        <input type="text" name="search" placeholder="Search artists..."
            value="<?php echo htmlspecialchars($searchQuery); ?>" class="form-control d-inline-block w-auto"
            style="max-width: 200px;">
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if (!empty($searchQuery)) { ?>
            <a href="/dashboard/artists" class="btn btn-secondary text-white">Clear</a>
        <?php } ?>
    </div>

    <!-- Sort -->
    <div class="mt-2 mt-md-0 d-flex flex-wrap align-items-center gap-2">
        <select name="sort" id="sortSelect" class="form-select" style="width: 150px;">
            <option value="" disabled selected>Sort by...</option>
            <option value="name" <?php echo ($sortColumn == 'name') ? 'selected' : ''; ?>>Name</option>
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
    <?php if (!empty($artists)) { ?>
        <?php foreach ($artists as $artist) { ?>
            <?php include __DIR__ . '/../../../components/dashboard/cards/artist_card.php'; ?>
        <?php } ?>
    <?php } else { ?>
        <p>No artists found.</p>
    <?php } ?>
</div>

<script src="/assets/js/utils.js"></script>