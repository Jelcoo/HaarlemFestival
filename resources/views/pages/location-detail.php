<?php
$header_name = htmlspecialchars($location->name ?? 'Location Not Found');
$header_description = $location ? htmlspecialchars($location->preview_description) : "The location you're looking for doesn't exist.";
$header_dates = 'July 25 - 27, 2025';
$header_image = $location->assets[0] ?? '/assets/img/locations/placeholder-location.png';

include_once __DIR__ . '/../components/header.php';
?>

<div class="container location-grid">
    <?php if (!$location) { ?>
        <h2 class="text-center">Location Not Found</h2>
        <p class="text-center">The location you're looking for doesn't exist.</p>
    <?php } else { ?>
        <div class="row">
            <div class="col-md-6 location-img-container">
                <img src="<?= htmlspecialchars($location->assets[0] ?? '/assets/img/locations/placeholder-location.png') ?>" alt="Location Image" class="img-fluid rounded">
            </div>
            <div class="col-md-6 location-details">
                <h1 class="location-title"><?= htmlspecialchars($location->name) ?></h1>
                <p><?= htmlspecialchars($location->main_description) ?></p>

                <?php if ($location->address): ?>
                    <p><strong>Address:</strong> <?= htmlspecialchars($location->address) ?></p>
                <?php endif; ?>

                <?php if ($location->coordinates): ?>
                    <p><strong>Coordinates:</strong> <?= htmlspecialchars($location->coordinates) ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php } ?>
</div>

<style>
    .location-grid {
        padding: 50px 0;
    }
    .location-img-container {
        text-align: center;
    }
    .location-img-container img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 10px;
    }
    .location-details {
        padding: 20px;
    }
    .location-title {
        font-size: 2rem;
        font-weight: bold;
    }
    .header-section {
        background-color: #1F4E66;
    }
</style>
