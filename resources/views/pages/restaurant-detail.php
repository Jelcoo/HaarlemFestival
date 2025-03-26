<?php
$header_name = htmlspecialchars($restaurant->location->name);
$header_description = htmlspecialchars($restaurant->location->preview_description);
$header_dates = 'July 25 – 27, 2025';
$header_image = $header_image ?? '/assets/img/placeholder-restaurant.png';

include_once __DIR__ . '/../components/header.php';
?>

<div class="yummy-detail-wrapper">

    <!-- Welcome Message -->
    <section class="welcome-section text-center">
        <h2>Welcome to <strong><?= htmlspecialchars($restaurant->location->name) ?></strong></h2>
        <p>During the Festival, you can reserve a table for one of our exclusive sessions,<br>
            where we’ll serve a specially crafted festival menu.<br>
            You’ll find the prices and reservation fees listed here.<br>
            If you're coming as a group, please ensure there are enough seats available,<br>
            and don’t forget to mention any special requirements.<br>
            We look forward to welcoming you and hope you enjoy your dining experience!
        </p>
    </section>

    <!-- Description & Map -->
    <section class="description-map-box">
        <div class="info-box">
            <p><?= nl2br(htmlspecialchars($restaurant->location->main_description)) ?></p>
            <div class="rating-stars">
                <?php for ($i = 0; $i < $restaurant->rating; $i++): ?>
                    <span class="star">&#9733;</span>
                <?php endfor; ?>
            </div>
            <em><?= htmlspecialchars($restaurant->restaurant_type) ?></em>
        </div>
        <div class="map-box">
            <div id="map" style="height: 200px;"></div>
            <p class="mt-2 text-center">
                <strong><?= htmlspecialchars($restaurant->location->address) ?></strong>
            </p>
        </div>
    </section>

    <!-- Session Times -->
    <section class="session-times text-center">
        <h3>Session Times by Day</h3>
        <div class="session-bar">
            <?php
            $days = [];

            foreach ($events as $event) {
                $dateKey = date('l j', strtotime($event->start_date)); // e.g. "Friday 25"
                $time = date('H.i', strtotime($event->start_time));    // e.g. "18.00"

                $days[$dateKey][] = $time;
            }

            foreach ($days as $day => $times) {
                echo "<div class='session-day'><strong>{$day}</strong><br><br>";
                foreach (array_unique($times) as $time) {
                    echo "<span class='session-time'>{$time}</span> ";
                }
                echo "</div><br>";
            }
            ?>
        </div>
    </section>

    <!-- Restaurant Images -->
    <?php if (!empty($restaurant_images)): ?>
        <section class="restaurant-images text-center">
            <?php foreach ($restaurant_images as $img): ?>
                <img src="<?= htmlspecialchars($img->getUrl()) ?>" class="restaurant-photo" alt="Restaurant photo">
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <!-- Price & Reserve -->
    <section class="reservation-box">
        <div class="price-info">
            <div><i class="fa fa-user"></i> &nbsp; € <?= number_format($adult_price, 2) ?></div>
            <div><i class="fa fa-child"></i> &nbsp; € <?= number_format($kids_price, 2) ?> <small>* Children under 12</small></div>
            <?php if ($has_price_variation): ?>
                <p class="text-warning mt-2"><small>* Prices may vary slightly depending on the session</small></p>
            <?php endif; ?>
        </div>

        <div class="logo-box text-center">
            <img src="<?= htmlspecialchars($logo_image) ?>" alt="Restaurant Logo">
            <p>Seats Available<br><strong><?= $restaurant->seats_available ?></strong></p>
            <a href="#" class="btn btn-warning mt-2">+ Reserve Table</a>
        </div>
        <div class="info-text">
            <p>Reservation is mandatory. A reservation fee of €10 per person will be charged when a reservation is made on The Festival site. This will be deducted from the final check on visiting the restaurant.</p>
            <p>When reserving, please share any special requests, like wheelchair access or allergies, to ensure a comfortable experience.</p>
        </div>
    </section>

    <!-- Festival Menu -->
    <section class="menu-box">
        <h4>Festival Menu</h4>
        <div class="menu-group">
            <strong>Starter</strong>
            <p><?= nl2br(htmlspecialchars($restaurant->menu_starter)) ?></p>
        </div>
        <div class="menu-group">
            <strong>Main Course</strong>
            <p><?= nl2br(htmlspecialchars($restaurant->menu_main)) ?></p>
        </div>
        <div class="menu-group">
            <strong>Dessert</strong>
            <p><?= nl2br(htmlspecialchars($restaurant->menu_dessert)) ?></p>
        </div>
    </section>
</div>

<!-- 🍽️ STYLING -->
<style>
.yummy-detail-wrapper {
    padding: 40px 20px;
    max-width: 1000px;
    margin: auto;
}
.welcome-section {
    margin-bottom: 40px;
}
.description-map-box {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 40px;
}
.info-box, .map-box {
    flex: 1 1 45%;
    background-color: #2c4d69;
    color: white;
    padding: 20px;
    border-radius: 10px;
}
.rating-stars {
    margin: 10px 0;
}
.star {
    color: gold;
    font-size: 20px;
}
.session-times {
    margin: 40px 0;
}
.session-bar {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 15px;
}
.session-time {
    background-color: #226C92;
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: bold;
}
.restaurant-images {
    margin: 30px 0;
}
.restaurant-photo {
    max-width: 100%;
    border-radius: 10px;
    margin-bottom: 15px;
}
.reservation-box {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    background-color: #e6f0f7;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 40px;
}
.price-info, .logo-box, .info-text {
    flex: 1 1 30%;
}
.logo-box img {
    max-width: 120px;
    margin-bottom: 10px;
}
.menu-box {
    background-color: #2c4d69;
    color: white;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 60px;
}
.menu-box h4 {
    margin-bottom: 20px;
}
.menu-group {
    margin-bottom: 15px;
}
</style>

<!-- 🗺️ MAP SCRIPT (Leaflet) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('map').setView([52.3808, 4.6368], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

<?php if ($restaurant->location->coordinates): ?>
    const coords = "<?= $restaurant->location->coordinates ?>".split(',').map(Number);
    L.marker(coords).addTo(map).bindPopup(`<strong><?= htmlspecialchars($restaurant->location->name) ?></strong><br><?= htmlspecialchars($restaurant->location->address) ?>`);
    setTimeout(() => map.invalidateSize(), 200);
<?php endif; ?>
</script>
