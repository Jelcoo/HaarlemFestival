<!-- Title and Create Button -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Artist Management</h2>
    <form action="/dashboard/artists" method="POST">
        <button type="submit" class="btn btn-success" name="action" value="export">Export to CSV</button>
        <button type="submit" class="btn btn-primary" name="action" value="create">Create New Artist</button>
    </form>
</div>

<!-- Status message -->
<?php if (!empty($status['message'])) : ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($status['message']); ?>
    </div>
<?php endif; ?>

<!-- Sort -->
<form method="GET" action="/dashboard/artists" class="mb-3 d-flex justify-content-between align-items-center">
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
    <div class="d-flex align-items-center gap-2">
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
            <?php include __DIR__ . '/../../../components/dashboard/artist_card.php'; ?>
        <?php } ?>
    <?php } else { ?>
        <p>No artists found.</p>
    <?php } ?>
</div>

<script>
    function updateURL() {
        let sort = document.getElementById('sortSelect').value;
        let direction = document.getElementById('directionSelect').value;
        let searchParams = new URLSearchParams(window.location.search);

        if (sort) {
            searchParams.set('sort', sort);
        } else {
            searchParams.delete('sort');
        }

        if (direction) {
            searchParams.set('direction', direction);
        } else {
            searchParams.delete('direction');
        }

        window.location.href = window.location.pathname + '?' + searchParams.toString();
    }

    function resetSort() {
        let searchParams = new URLSearchParams(window.location.search);

        searchParams.delete('sort');
        searchParams.delete('direction');

        window.location.href = window.location.pathname + '?' + searchParams.toString();
    }
</script>
