<?php

use App\Enum\EventTypeEnum;

if (!isset($location)) {
    return;
}
$showEditForm = isset($_GET['edit']) && $_GET['edit'] == $location->id;
$showDetails = isset($_GET['details']) && $_GET['details'] == $location->id;
?>

<div class="col-md-4">
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">
                <?php echo htmlspecialchars($location->name ?? 'Unknown Location'); ?>
            </h5>

            <?php if (!$showEditForm) { ?>
                <!-- Location Details -->
                <p class="card-text"><strong>Event Type:</strong>
                    <?php echo htmlspecialchars($location->event_type->name ?? 'Unknown Event Type'); ?>
                </p>
                <p class="card-text"><strong>Address:</strong>
                    <?php echo htmlspecialchars($location->address ?? 'No Address Available'); ?>
                </p>
                <p class="card-text"><strong>Coordinates:</strong>
                    <?php echo htmlspecialchars($location->coordinates ?? 'No Coordinates'); ?>
                </p>

                <?php if (!$showDetails) { ?>
                    <!-- Show More Button -->
                    <a href="/dashboard/locations?details=<?php echo $location->id; ?>" class="btn btn-info btn-sm">Show
                        More</a>
                <?php } else { ?>
                    <!-- Full Descriptions -->
                    <p class="card-text"><strong>Preview Description:</strong>
                        <?php echo htmlspecialchars($location->preview_description ?? 'No Preview Description'); ?>
                    </p>
                    <p class="card-text"><strong>Main Description:</strong>
                        <?php echo htmlspecialchars($location->main_description ?? 'No Main Description'); ?>
                    </p>

                    <!-- Show Less Button -->
                    <a href="/dashboard/locations" class="btn btn-secondary btn-sm">Show Less</a>
                <?php } ?>

                <!-- Action Buttons -->
                <form action="/dashboard/locations" method="POST" class="d-inline">
                    <input type="hidden" name="id" value="<?php echo $location->id; ?>">
                    <button type="submit" class="btn btn-warning btn-sm" name="action" value="edit">Edit</button>
                    <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete">Delete</button>
                </form>

            <?php } ?>
        </div>
    </div>
</div>