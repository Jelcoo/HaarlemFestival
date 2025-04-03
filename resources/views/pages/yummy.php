<?php

$header_name = 'Yummy';
$header_description = 'The Yummy! Haarlem Food Festival page showcases top restaurants with special Festival Menus at reduced prices. Explore detailed profiles with images, chef info, menus, and contact details. Reserve directly, check availability, and request dietary accommodations for an unforgettable culinary experience.';
$header_dates = 'July 25 - 27, 2025';
$header_image = '/assets/img/events/slider/yummy.png';

include_once __DIR__ . '/../components/header.php';
?>
<link rel="stylesheet" href="/assets/css/yummy.css">
<!-- Festival Information Banner -->
<div class="container mt-5">
    <div class="info-box">
        <p><strong>During the Festival, participating restaurants offer a special menu</strong> at a reduced price, <br>
            including a <strong>€10 reservation fee</strong> (deducted from the final bill).</p>
        <p>A children's price is also available.</p>
        <p>Each restaurant hosts 2-3 sessions lasting <strong>1.5 to 2 hours</strong>, <br> where tables are reserved
            exclusively for festival guests.</p>
        <p>Your meal is carefully prepared <strong>during the session</strong> to ensure a smooth and enjoyable
            experience.</p>
        <p>Seats are limited, so be sure to check <br><strong>availability and the type</strong> of cuisine each
            restaurant offers before booking.</p>
    </div>
</div>

<div class="container">
    <h2 class="restaurants">Our Restaurants</h2>
    <div class="restaurant-container">
        <?php foreach ($restaurants as $key => $restaurant) {
            $isEven = $key % 2 == 0;
            ?>
            <div class="restaurant-row <?php echo $isEven ? 'even-row' : 'odd-row'; ?>">
                <div class="icon-wrapper <?php echo $isEven ? 'icon-left' : 'icon-right'; ?>">
                    <div class="restaurant-icon">
                        <img src="<?php echo $restaurant->assets[1]->getUrl(); ?>"
                            alt="<?php echo htmlspecialchars($restaurant->location->name); ?> Icon">
                    </div>
                </div>
                <div class="restaurant-card <?php echo $isEven ? 'card-right' : 'card-left'; ?>">
                    <?php if ($isEven) { ?>
                        <div class="restaurant-image">
                            <div class="image-wrapper">
                                <img src="<?php echo $restaurant->assets[0]->getUrl(); ?>"
                                    alt="<?php echo htmlspecialchars($restaurant->location->name); ?>">
                            </div>
                        </div>
                        <div class="restaurant-content">
                            <div class="restaurant-header">
                                <h3><?php echo htmlspecialchars($restaurant->location->name); ?></h3>
                                <div class="stars">
                                    <?php for ($i = 0; $i < $restaurant->rating; ++$i) { ?>
                                        <span>&#9733;</span>
                                    <?php } ?>
                                </div>
                            </div>
                            <p><?php echo $restaurant->location->preview_description; ?></p>
                            <div class="restaurant-footer">
                                <div class="cuisine-container">
                                    <div class="cuisine">
                                        <em><?php echo htmlspecialchars($restaurant->restaurant_type); ?></em>
                                    </div>
                                </div>
                                <div class="button-container">
                                    <a href="/yummy/<?php echo str_replace(' ', '_', $restaurant->location->name) . '_' . $restaurant->id; ?>"
                                        class="btn btn-primary btn-custom-yellow">
                                        Visit <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="restaurant-content">
                            <div class="restaurant-header">
                                <div class="stars">
                                    <?php for ($i = 0; $i < $restaurant->rating; ++$i) { ?>
                                        <span>&#9733;</span>
                                    <?php } ?>
                                </div>
                                <h3><?php echo htmlspecialchars($restaurant->location->name); ?></h3>
                            </div>
                            <p><?php echo $restaurant->location->preview_description; ?></p>
                            <div class="restaurant-footer">
                                <div class="cuisine-container">
                                    <div class="cuisine">
                                        <em><?php echo htmlspecialchars($restaurant->restaurant_type); ?></em>
                                    </div>
                                </div>
                                <div class="button-container">
                                    <a href="/yummy/<?php echo str_replace(' ', '_', $restaurant->location->name) . '_' . $restaurant->id; ?>"
                                        class="btn btn-primary btn-custom-yellow">
                                        Visit <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="restaurant-image">
                            <div class="image-wrapper">
                                <img src="<?php echo $restaurant->assets[0]->getUrl(); ?>"
                                    alt="<?php echo htmlspecialchars($restaurant->location->name); ?>">
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<button data-bs-toggle="modal" data-bs-target="#socialMediaModal" class="btn btn-custom-yellow floating-button">
    <i class="fa-solid fa-share-from-square"></i> <span>Share</span>
</button>