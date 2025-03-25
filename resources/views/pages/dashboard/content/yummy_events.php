<?php
$restaurantMap = [];
foreach ($restaurants as $r) {
    $restaurantMap[$r->id] = [
        'location_name' => $r->location->name ?? '-',
    ];
}
foreach ($events as $event) {
    $restaurantInfo = $restaurantMap[$event->restaurant_id] ?? ['location_name' => '-'];
    $event->restaurant_name = $restaurantInfo['location_name'];
}
?>

<!-- Title and Create Button -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Yummy Event Management</h2>
    <div>
        <a href="/dashboard/events/yummy/export" class="btn btn-success">Export to CSV</a>
        <a href="/dashboard/events/yummy/create" class="btn btn-primary">Create New Yummy Event</a>
    </div>
</div>

<!-- Status Message -->
<?php if (!empty($status['message'])) { ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($status['message']); ?>
    </div>
<?php } ?>

<!-- Search & Sort -->
<form method="GET" action="/dashboard/events/yummy" class="mb-3 d-flex justify-content-between align-items-center">
    <!-- Search -->
    <div class="d-flex align-items-center gap-2">
        <input type="text" name="search" placeholder="Search events..."
            value="<?php echo htmlspecialchars($searchQuery); ?>" class="form-control d-inline-block w-auto"
            style="max-width: 200px;">
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if (!empty($searchQuery)) { ?>
            <a href="/dashboard/events/yummy" class="btn btn-secondary text-white">Clear</a>
        <?php } ?>
    </div>

    <!-- Sort -->
    <div class="d-flex align-items-center gap-2">
        <select name="sort" id="sortSelect" class="form-select" style="width: 150px;">
            <option value="" disabled selected>Sort by...</option>
            <?php foreach ($columns as $key => $data): ?>
                <?php if ($data['sortable']): ?>
                    <option value="<?= $key ?>" <?= ($sortColumn === $key) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($data['label']) ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>

        <select name="direction" id="directionSelect" class="form-select" style="width: 150px;">
            <option value="asc" <?php echo ($sortDirection == 'asc') ? 'selected' : ''; ?>>Ascending</option>
            <option value="desc" <?php echo ($sortDirection == 'desc') ? 'selected' : ''; ?>>Descending</option>
        </select>

        <button type="button" class="btn btn-primary" onclick="updateURL()">Apply</button>
        <a href="/dashboard/events/yummy" class="btn btn-secondary">Reset</a>
    </div>
</form>

<!-- Table -->
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
                                $value = $event->$key ?? '';
                                if ($value instanceof BackedEnum) {
                                    $value = $value->value;
                                } elseif (in_array($key, ['start_date', 'end_date']) && $value) {
                                    $value = \Carbon\Carbon::parse($value)->format('d-m-Y');
                                } elseif (in_array($key, ['start_time', 'end_time']) && $value) {
                                    $value = \Carbon\Carbon::parse($value)->format('H:i');
                                } elseif (in_array($key, ['kids_price', 'adult_price', 'reservation_cost']) && is_numeric($value)) {
                                    $value = '€' . number_format($value, 2);
                                } elseif ($key === 'vat') {
                                    $value = number_format($value * 100, 0) . '%';
                                }
                            ?>
                            <?= htmlspecialchars($value) ?>
                        </td>
                    <?php endforeach; ?>
                    <td class="d-flex gap-2">
                        <a href="/dashboard/events/yummy/edit?id=<?= $event->id ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form action="/dashboard/events/yummy/delete" method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?= $event->id ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?= count($columns) + 1 ?>">No events found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>