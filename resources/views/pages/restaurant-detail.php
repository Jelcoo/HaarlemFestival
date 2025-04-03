<?php
$header_name = htmlspecialchars($restaurant->location->name);
$header_description = htmlspecialchars($restaurant->location->preview_description);
$header_dates = 'July 25 – 27, 2025';
$header_image = !empty($headerAsset) ? $headerAsset[0]->getUrl() : '/assets/img/placeholder2.png';

include_once __DIR__ . '/../components/header.php';
?>

<div class="yummy-detail-wrapper">
    <section class="welcome-section text-center">
        <h2>Welcome to <strong><?php echo htmlspecialchars($restaurant->location->name); ?></strong></h2>
        <p>During the Festival, you can reserve a table for one of our exclusive sessions,<br>
            where we’ll serve a specially crafted festival menu.<br>
            You’ll find the prices and reservation fees listed here.<br>
            If you're coming as a group, please ensure there are enough seats available,<br>
            and don’t forget to mention any special requirements.<br>
            We look forward to welcoming you and hope you enjoy your dining experience!
        </p>
    </section>

    <section class="description-map-box">
        <div class="info-box">
            <p><?php echo nl2br(htmlspecialchars($restaurant->location->main_description)); ?></p>
            <strong>Reservation is mandatory.</strong>
            <div class="rating-stars mt-2">
                <?php for ($i = 0; $i < $restaurant->rating; ++$i) { ?>
                    <span class="star">★</span>
                <?php } ?>
            </div>
            <div class="cuisine-box"><?php echo htmlspecialchars($restaurant->restaurant_type); ?></div>
        </div>
        <div class="map-box">
            <div id="map" style="height: 100%; min-height: 240px;"></div>
        </div>
    </section>

    <section class="session-times text-center">
        <h3>Session Times by Day</h3>
        <div class="session-bar">
            <?php
            $days = [];
foreach ($events as $event) {
    $day = date('l j', strtotime($event->start_date));
    $time = date('H.i', strtotime($event->start_time));
    $days[$day][] = $time;
}
foreach ($days as $day => $times) {
    echo "<div class='session-day'><strong>{$day}</strong><br><br>";
    foreach (array_unique($times) as $time) {
        echo "<span class='session-time'>{$time}</span> ";
    }
    echo '</div>';
}
?>
        </div>
    </section>

    <section class="menu-reservation-section">
        <div class="menu-box">
            <h4>Festival Menu</h4>
            <?php
$menu = $restaurant->menu;
$sections = ['Starter', 'Main Course', 'Dessert'];
foreach ($sections as $section) {
    if (stripos($menu, $section) !== false) {
        preg_match("/$section\s+(.*?)(?=(Starter|Main Course|Dessert|$))/si", $menu, $match);
        if (!empty($match[1])) {
            echo "<h5 class='menu-section-title'>{$section}</h5>";
            echo "<p class='menu-item'>" . htmlspecialchars(trim($match[1])) . '</p>';
        }
    }
}
?>
        </div>

        <div class="sidebar-right">
            <?php if (!empty($logoAsset)) { ?>
                <img src="<?php echo htmlspecialchars($logoAsset[0]->getUrl()); ?>" alt="Restaurant Logo" class="logo-img">
            <?php } ?>
            <div class="prices mt-2">
                <div><i class="fa fa-user"></i> &euro; <?php echo number_format($adult_price, 2); ?></div>
                <div><i class="fa fa-child"></i> &euro; <?php echo number_format($kids_price, 2); ?> <small>* Children under 12</small></div>
                <?php if ($has_price_variation) { ?>
                    <p class="text-warning"><small>* Prices may vary slightly</small></p>
                <?php } ?>
            </div>
            <a href="#" class="btn btn-warning mt-3">+ Reserve Table</a>
        </div>
    </section>

    <section class="reservation-info-box">
        <p>A reservation fee of &euro;10 per person will be charged when booking through the Festival site. This will be deducted from the final bill at the restaurant.</p>
        <p>Please share any special requests such as allergies or wheelchair access to ensure a great experience.</p>
    </section>

    <?php if (!empty($extraAssets)) { ?>
        <section class="restaurant-images text-center">
            <?php foreach ($extraAssets as $img) { ?>
                <img src="<?php echo htmlspecialchars($img->getUrl()); ?>" class="restaurant-photo" alt="Restaurant photo">
            <?php } ?>
        </section>
    <?php } ?>
</div>

<style>
.header-section {
    background-color: #1F4E66;
}
.yummy-detail-wrapper {
    padding: 40px 20px;
    max-width: 1000px;
    margin: auto;
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
.rating-stars { margin-top: 10px; }
.star { color: gold; font-size: 20px; }
.session-bar {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
    margin: 20px 0;
}
.session-time {
    background-color: #226C92;
    color: white;
    padding: 8px 16px;
    border-radius: 25px;
    font-weight: bold;
}
.menu-reservation-section {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    margin-bottom: 0;
}
.menu-box {
    flex: 1 1 60%;
    background-color: #2c4d69;
    color: white;
    padding: 20px;
    border-radius: 10px;
}
.menu-section-title {
    font-weight: bold;
    margin-top: 15px;
}
.sidebar-right {
    flex: 1 1 35%;
    background-color: #f7f7f7;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
}
.logo-img {
    max-width: 100px;
    margin-bottom: 10px;
}
.cuisine-box {
    margin-top: 10px;
    background-color: #226C92;
    display: inline-block;
    padding: 5px 12px;
    border-radius: 6px;
    font-style: italic;
}
.reservation-info-box {
    margin-top: 30px;
    background-color: #e6f0f7;
    padding: 20px;
    border-radius: 10px;
    font-size: 1em;
    line-height: 1.6em;
}
.restaurant-photo {
    max-width: 40%;
    border-radius: 10px;
    margin: 10px;
}
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('map').setView([52.3808, 4.6368], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

<?php if ($restaurant->location->coordinates) { ?>
    const coords = "<?php echo $restaurant->location->coordinates; ?>".split(',').map(Number);
    L.marker(coords).addTo(map).bindPopup(`<strong><?php echo htmlspecialchars($restaurant->location->name); ?></strong><br><?php echo htmlspecialchars($restaurant->location->address); ?>`);
    setTimeout(() => map.invalidateSize(), 200);
<?php } ?>
</script>