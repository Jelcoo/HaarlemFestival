<!-- Title and Create Button -->
<div class="d-block d-md-flex justify-content-between align-items-center mb-3">
    <h2>Dance Event Management</h2>
    <div class="d-flex gap-2">
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
<form method="GET" action="/dashboard/events/dance" class="mb-3 d-block d-md-flex justify-content-between align-items-center">
    <!-- Search -->
    <div class="d-flex align-items-center gap-2">
        <input type="text" name="search" placeholder="Search events..."
            value="<?php echo htmlspecialchars($searchQuery); ?>" class="form-control d-inline-block w-auto"
            style="max-width: 200px;">
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if (!empty($searchQuery)) { ?>
            <a href="/dashboard/events/dance" class="btn btn-secondary text-white">Clear</a>
        <?php } ?>
    </div>

    <!-- Sort -->
    <div class="mt-2 mt-md-0 d-flex flex-wrap align-items-center gap-2">
        <select name="sort" id="sortSelect" class="form-select" style="width: 150px;">
            <option value="" disabled selected>Sort by...</option>
            <?php foreach ($columns as $key => $data) { ?>
                <?php if ($data['sortable']) { ?>
                    <option value="<?php echo $key; ?>" <?php echo ($sortColumn === $key) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($data['label']); ?>
                    </option>
                <?php } ?>
            <?php } ?>
        </select>

        <select name="direction" id="directionSelect" class="form-select" style="width: 150px;">
            <option value="asc" <?php echo ($sortDirection == 'asc') ? 'selected' : ''; ?>>Ascending</option>
            <option value="desc" <?php echo ($sortDirection == 'desc') ? 'selected' : ''; ?>>Descending</option>
        </select>

        <button type="button" class="btn btn-primary" onclick="updateURL()">Apply</button>
        <a href="/dashboard/events/dance" class="btn btn-secondary">Reset</a>
    </div>
</form>

<!-- Dance Events Table View -->
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <?php foreach ($columns as $key => $data) { ?>
                    <?php
                        $newDirection = ($sortColumn === $key && $sortDirection === 'asc') ? 'desc' : 'asc';
                    $sortUrl = "?sort={$key}&direction={$newDirection}";
                    if (!empty($searchQuery)) {
                        $sortUrl .= '&search=' . urlencode($searchQuery);
                    }
                    ?>
                    <th>
                        <?php if ($data['sortable']) { ?>
                            <a href="<?php echo $sortUrl; ?>"><?php echo htmlspecialchars($data['label']); ?></a>
                        <?php } else { ?>
                            <?php echo htmlspecialchars($data['label']); ?>
                        <?php } ?>
                    </th>
                <?php } ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($events)) { ?>
                <?php foreach ($events as $event) { ?>
                    <tr>
                        <?php foreach ($columns as $key => $col) { ?>
                            <td>
                                <?php
                                    $value = $event[$key] ?? '';
                            if (in_array($key, ['start_date', 'end_date']) && $value) {
                                $value = Carbon\Carbon::parse($value)->format('d-m-Y');
                            } elseif (in_array($key, ['start_time', 'end_time']) && $value) {
                                $value = Carbon\Carbon::parse($value)->format('H:i');
                            } elseif ($key === 'price' && is_numeric($value)) {
                                $value = '€' . number_format($value, 2);
                            } elseif ($key === 'vat') {
                                $value = number_format((float) $value * 100, 0) . '%';
                            }
                            ?>
                                <?php echo htmlspecialchars($value); ?>
                            </td>
                        <?php } ?>
                        <td class="d-flex gap-2">
                            <a href="/dashboard/events/dance/edit?id=<?php echo $event['event_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="/dashboard/events/dance/delete" method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $event['event_id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="<?php echo count($columns) + 1; ?>">No dance events found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<div>

<script src="/assets/js/utils.js"></script>