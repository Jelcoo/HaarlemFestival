<?php
$header_name = htmlspecialchars($location->name ?? 'Location Not Found');
$header_description = $location ? htmlspecialchars($location->preview_description) : "The location you're looking for doesn't exist.";
$header_dates = 'July 25 - 27, 2025';
$header_image = !empty($headerAsset) ? $headerAsset[0]->getUrl() : '/assets/img/placeholder2.png';

include_once __DIR__ . '/../components/header.php';
/* @var \App\Models\Location $location */
?>

<div class="container location-grid">
    <?php if (!$location) { ?>
        <h2 class="text-center">Location Not Found</h2>
        <p class="text-center">The location you're looking for doesn't exist.</p>
    <?php } else { ?>
        <div class="row">
            <div class="col-md-12 location-details">
                <h1 class="location-title"><?php echo htmlspecialchars($location->name); ?></h1>

                <h3>History and Significance</h3>
                <p><?php echo nl2br(htmlspecialchars($location->main_description)); ?></p>

                <?php if ($location->address) { ?>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($location->address); ?></p>
                <?php } ?>

                <div class="location-map-gallery">
                    <?php if ($location->coordinates) { ?>
                        <div id="map" class="map-container"></div>
                    <?php } ?>

                    <?php if (!empty($extraAssets)) { ?>
                        <div class="gallery-grid">
                            <?php foreach (array_slice($extraAssets, 0, 5) as $asset) { ?>
                                <img src="<?php echo $asset->getUrl(); ?>" alt="Gallery Image">
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php if ($location && $location->coordinates) { ?>
    <script>
        const map = L.map('map').setView([52.3808, 4.6368], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const coords = "<?php echo $location->coordinates; ?>".split(',').map(Number);
        L.marker(coords).addTo(map).bindPopup(`<strong><?php echo htmlspecialchars($location->name); ?></strong><br><?php echo htmlspecialchars($location->address); ?>`);
        setTimeout(() => map.invalidateSize(), 200);
    </script>
<?php } ?>

<style>
    .header-section {
        background-color: #1F4E66;
    }
</style>
