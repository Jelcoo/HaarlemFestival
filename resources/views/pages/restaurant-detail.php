<?php
$header_name = htmlspecialchars($restaurant->location->name ?? 'Restaurant Not Found');
$header_description = $restaurant ? htmlspecialchars($restaurant->location->preview_description) : "The restaurant you're looking for doesn't exist.";
$header_dates = 'July 25 - 27, 2025';
$header_image = '/assets/img/events/slider/yummy.png';

include_once __DIR__ . '/../components/header.php';
?>

<div class="container restaurant-grid">
    <?php if (!$restaurant) { ?>
        <h2 class="text-center">Restaurant Not Found</h2>
        <p class="text-center">The restaurant you're looking for doesn't exist.</p>
    <?php } else { ?>
        <div class="row">
            <div class="col-md-6 restaurant-img-container">
                <img src="<?= htmlspecialchars($restaurant->image ?? '/assets/img/default-restaurant.jpg') ?>" alt="Restaurant Image" class="img-fluid rounded">
            </div>
            <div class="col-md-6 restaurant-details">
                <h1 class="restaurant-title"><?= htmlspecialchars($restaurant->location->name) ?></h1>
                <p><strong>ID:</strong> <?= htmlspecialchars($restaurant->id) ?></p>
                <p><strong>Type:</strong> <?= htmlspecialchars($restaurant->restaurant_type) ?></p>
                <p><strong>Rating:</strong> <?= htmlspecialchars($restaurant->rating) ?>/5</p>
                <p><strong>Menu:</strong> <?= htmlspecialchars($restaurant->menu) ?></p>
                <p><strong>Location ID:</strong> <?= htmlspecialchars($restaurant->location_id) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($restaurant->location->address) ?></p>
                <p><strong>Coordinates:</strong> <?= htmlspecialchars($restaurant->location->coordinates ?? 'N/A') ?></p>
                <p><strong>Preview Description:</strong> <?= htmlspecialchars($restaurant->location->preview_description) ?></p>
                <p><strong>Main Description:</strong> <?= htmlspecialchars($restaurant->location->main_description) ?></p>
                
                <?php if (!empty($restaurant->assets)) { ?>
                    <p><strong>Restaurant Assets:</strong></p>
                    <ul>
                        <?php foreach ($restaurant->assets as $asset) { ?>
                            <li><?= htmlspecialchars($asset) ?></li>
                        <?php } ?>
                    </ul>
                <?php } ?>

                <?php if (!empty($restaurant->location->assets)) { ?>
                    <p><strong>Location Assets:</strong></p>
                    <ul>
                        <?php foreach ($restaurant->location->assets as $asset) { ?>
                            <li><?= htmlspecialchars($asset) ?></li>
                        <?php } ?>
                    </ul>
                <?php } ?>

                <a href="/yummy" class="btn btn-custom-yellow"><i class="fa-solid fa-arrow-left"></i> Back to Yummy</a>
            </div>
        </div>
    <?php } ?>
</div>

<style>
    .restaurant-grid {
        padding: 50px 0;
    }
    .restaurant-img-container {
        text-align: center;
    }
    .restaurant-img-container img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 10px;
    }
    .restaurant-details {
        padding: 20px;
    }
    .restaurant-title {
        font-size: 2rem;
        font-weight: bold;
    }
    .btn-custom-yellow {
        background-color: #f1c40f;
        border: none;
        padding: 10px 20px;
        font-weight: bold;
        border-radius: 5px;
    }
    .btn-custom-yellow:hover {
        background-color: #e1b30f;
    }
    .header-section {
        background-color: #1F4E66;
    }
</style>
