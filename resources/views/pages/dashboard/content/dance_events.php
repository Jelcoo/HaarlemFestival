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

<!-- Dance Events Table View -->
<table class="table table-bordered">
    <thead>
        <tr>
            <?php foreach ($columns as $key => $data): ?>
                <?php
                    $newDirection = ($sortColumn === $key && $sortDirection === 'asc') ? 'desc' : 'asc';
                    $sortUrl = "?sort={$key}&direction={$newDirection}";
                    if (!empty($searchQuery)) {
                        $sortUrl .= '&search=' . urlencode($searchQuery);
                    }
                ?>
                <th>
                    <?php if ($data['sortable']): ?>
                        <a href="<?= $sortUrl ?>"><?= htmlspecialchars($data['label']) ?></a>
                    <?php else: ?>
                        <?= htmlspecialchars($data['label']) ?>
                    <?php endif; ?>
                </th>
            <?php endforeach; ?>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <tr>
                    <?php foreach ($columns as $key => $col): ?>
                        <td>
                            <?php
                                $value = $event[$key] ?? '';
                                if (in_array($key, ['start_date', 'end_date']) && $value) {
                                    $value = \Carbon\Carbon::parse($value)->format('d-m-Y');
                                } elseif (in_array($key, ['start_time', 'end_time']) && $value) {
                                    $value = \Carbon\Carbon::parse($value)->format('H:i');
                                } elseif ($key === 'price' && is_numeric($value)) {
                                    $value = '€' . number_format($value, 2);
                                } elseif ($key === 'vat') {
                                    $value = number_format((float) $value * 100, 0) . '%';
                                }
                            ?>
                            <?= htmlspecialchars($value) ?>
                        </td>
                    <?php endforeach; ?>
                    <td class="d-flex gap-2">
                        <a href="/dashboard/events/dance/edit?id=<?= $event['event_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form action="/dashboard/events/dance/delete" method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?= $event['event_id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?= count($columns) + 1 ?>">No dance events found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
