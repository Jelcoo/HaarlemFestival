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
                <img src="<?php echo htmlspecialchars($location->assets[0] ?? '/assets/img/locations/placeholder-location.png'); ?>" alt="Location Image" class="img-fluid rounded">
            </div>
            <div class="col-md-6 location-details">
                <h1 class="location-title"><?php echo htmlspecialchars($location->name); ?></h1>

                <h3>History and Significance</h3>
                <p><?php echo nl2br(htmlspecialchars($location->main_description)); ?></p>

                <?php if ($location->address) { ?>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($location->address); ?></p>
                <?php } ?>

                <?php if ($location->coordinates) { ?>
                    <div id="map" style="height: 300px; border-radius: 10px; margin-top: 20px;"></div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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
    .location-details h3 {
        margin-top: 30px;
        font-size: 1.5rem;
        font-weight: bold;
    }
    .location-details ul {
        padding-left: 20px;
    }
    .location-details ul li {
        margin-bottom: 10px;
    }

    .modal-content {
        background-color: var(--secondary-accent);
        color: white;
        border-radius: 10px;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .section-content {
        font-size: 1.2rem;
        margin-bottom: 20px;
    }

    .quantity-control {
        background-color: white;
        border-radius: 5px;
        padding: 5px 15px;
        display: inline-flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .quantity-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
        cursor: pointer;
        padding: 0 10px;
    }

    .quantity-display {
        font-size: 1.2rem;
        margin: 0 15px;
        color: #333;
    }

    .book-btn {
        background-color: var(--buttons);
        border: none;
        color: black;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 5px;
        width: 100%;
        font-size: 1.2rem;
    }

    .book-btn:hover {
        background-color: var(--buttons-accent);
    }

    .modal-body {
        padding: 30px;
        text-align: center;
    }

    .price-text {
        font-size: 1.2rem;
        margin-bottom: 20px;
    }

</style>
