<?php
if (!isset($location)) {
    return;
}
$showDetails = isset($_GET['details']) && $_GET['details'] == $location->id;
?>

<div class="col-md-4">
    <div class="card mb-4">
        <div class="card-body d-flex align-items-start">
            <?php if (count($location->assets) > 0) { ?>
                <!-- Location Cover -->
                <img src="<?php echo $location->assets[0]->getUrl(); ?>" alt="Location Cover" class="img-fluid location-cover me-3">
            <?php } ?>

            <!-- Location Details -->
            <div class="w-100">
                <h5 class="card-title">
                    <?php echo htmlspecialchars($location->name ?? 'Unknown Location'); ?>
                </h5>

                <!-- Event Type -->
                <p class="card-text"><strong>Event Type:</strong>
                    <?php echo htmlspecialchars($location->event_type->name ?? 'Unknown Event Type'); ?>
                </p>

                <!-- Address -->
                <p class="card-text"><strong>Address:</strong>
                    <?php echo htmlspecialchars($location->address ?? 'No Address Available'); ?>
                </p>

                <!-- Coordinates -->
                <p class="card-text"><strong>Coordinates:</strong>
                    <?php echo !empty(trim($location->coordinates)) ? htmlspecialchars($location->coordinates) : 'No Coordinates'; ?>
                </p>

                <?php if ($showDetails) { ?>
                    <!-- Full Descriptions -->
                    <p class="card-text"><strong>Preview Description:</strong>
                        <?php echo !empty(trim($location->preview_description)) ? strip_tags($location->preview_description) : 'No Preview Description'; ?>
                    </p>

                    <p class="card-text"><strong>Main Description:</strong>
                        <?php echo !empty(trim($location->main_description)) ? strip_tags($location->main_description) : 'No Main Description'; ?>
                    </p>
                <?php } ?>

                <!-- Actions -->
                <div class="d-flex justify-content-between mt-3">
                    <a href="/dashboard/locations<?php echo $showDetails ? '' : '?details=' . $location->id; ?>"
                        class="btn btn-<?php echo $showDetails ? 'secondary' : 'info'; ?> btn-sm">
                        <?php echo $showDetails ? 'Show Less' : 'Show More'; ?>
                    </a>

                    <div>
                        <!-- Edit -->
                        <a href="/dashboard/locations/edit?id=<?= $location->id ?>" class="btn btn-warning btn-sm">Edit</a>

                        <!-- Delete -->
                        <form action="/dashboard/locations/delete" method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?php echo $location->id; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .location-cover {
        width: 8rem;
        height: auto;
    }
</style>
