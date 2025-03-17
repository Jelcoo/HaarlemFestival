<?php
if (!isset($artist)) {
    return;
}
$showDetails = isset($_GET['details']) && $_GET['details'] == $artist->id;
?>

<div class="col-md-4">
    <div class="card mb-4">
        <div class="card-body d-flex align-items-start">
            <?php if (count($artist->assets) > 0) { ?>
                <!-- Artist Cover -->
                <img src="<?php echo $artist->assets[0]->getUrl(); ?>" alt="Artist Cover" class="img-fluid artist-cover me-3">
            <?php } ?>

            <!-- Artist Details -->
            <div class="w-100">
                <h5 class="card-title">
                    <?php echo htmlspecialchars($artist->name ?? 'Unknown Artist'); ?>
                </h5>

                <!-- Preview Description -->
                <p class="card-text flex-grow-1"><strong>Preview Description:</strong>
                    <?php echo $showDetails
                        ? strip_tags($artist->preview_description ?? 'No Preview Description')
                        : strip_tags(substr($artist->preview_description ?? 'No Preview Description', 0, 100)) . '...'; ?>
                </p>

                <!-- Main Description -->
                <p class="card-text flex-grow-1"><strong>Main Description:</strong>
                    <?php echo $showDetails
                        ? strip_tags($artist->main_description ?? 'No Main Description')
                        : strip_tags(substr($artist->main_description ?? 'No Main Description', 0, 100)) . '...'; ?>
                </p>

                <!-- Iconic Albums -->
                <p class="card-text flex-grow-1"><strong>Iconic Albums:</strong>
                    <?php echo $showDetails
                        ? strip_tags($artist->iconic_albums ?? 'No Iconic Albums Listed')
                        : strip_tags(substr($artist->iconic_albums ?? 'No Iconic Albums Listed', 0, 100)) . '...'; ?>
                </p>

                <!-- Show More Button -->
                <div class="d-flex justify-content-between mt-3">
                    <a href="/dashboard/artists<?php echo $showDetails ? '' : '?details=' . $artist->id; ?>"
                        class="btn btn-<?php echo $showDetails ? 'secondary' : 'info'; ?> btn-sm">
                        <?php echo $showDetails ? 'Show Less' : 'Show More'; ?>
                    </a>

                    <!-- Actions -->
                    <form action="/dashboard/artists" method="POST" class="d-inline">
                        <input type="hidden" name="id" value="<?php echo $artist->id; ?>">
                        <button type="submit" class="btn btn-warning btn-sm" name="action" value="edit">Edit</button>
                        <button type="submit" class="btn btn-danger btn-sm ms-2" name="action"
                            value="delete">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .artist-cover {
        width: 8rem;
        height: auto;
    }
</style>
