<?php

$header_name = 'Yummy';
$header_description = 'The Yummy! Haarlem Food Festival page showcases top restaurants with special Festival Menus at reduced prices. Explore detailed profiles with images, chef info, menus, and contact details. Reserve directly, check availability, and request dietary accommodations for an unforgettable culinary experience.';
$header_dates = 'July 25 - 27, 2025';
$header_image = '/assets/img/events/slider/yummy.png';

include_once __DIR__ . '/../components/header.php';

$restaurants = [
    [
        'name' => 'Café de Roemer',
        'rating' => 4,
        'description' => 'A Haarlem favorite for over 30 years, Café de Roemer offers a menu with both classic and innovative dishes. Relax on the sunny terrace or in the cozy glass conservatory, perfect for any weather. Whether for lunch, dinner, or drinks, enjoy great food and warm hospitality!',
        'cuisine' => 'Dutch, Fish and Seafood, European',
        'image' => '/assets/img/restaurants/roemer.png',
        'icon' => 'icon1.png',
    ],
    [
        'name' => 'Ratatouille',
        'rating' => 4,
        'description' => 'Ratatouille Food and Wine in Haarlem, led by chef Jozua Jaring, offers a refined dining experience with dishes like Holstein tartar and Langoustine, paired with exclusive wines. Perfect for any occasion, the restaurant combines innovative flavors and exceptional hospitality for a memorable culinary journey.',
        'cuisine' => 'French, Fish and Seafood, European',
        'image' => '/assets/img/restaurants/ratatouille.png',
        'icon' => 'icon2.png',
    ],
    [
        'name' => 'Restaurant ML',
        'rating' => 4,
        'description' => 'Restaurant ML in Haarlem, awarded a Michelin star, offers bold dishes by chef Mark Gratama in a modern setting with an open kitchen. The menu blends French and international flavors, complemented by a focused wine list in an elegant ambiance.',
        'cuisine' => 'Dutch, Fish and Seafood, European',
        'image' => '/assets/img/restaurants/ml.png',
        'icon' => 'icon3.png',
    ],
    [
        'name' => 'Restaurant Fris',
        'rating' => 4,
        'description' => 'Restaurant Fris in Haarlem offers a refined dining experience, blending French and Asian influences in a creative interpretation of local ingredients. Chef Rick Swinkels welcomes guests with fresh, innovative dishes. Enjoy a high-quality dining experience in a welcoming atmosphere.',
        'cuisine' => 'Dutch, French, European',
        'image' => '/assets/img/restaurants/fris.png',
        'icon' => 'icon4.png',
    ],
    [
        'name' => 'New Vegas',
        'rating' => 3,
        'description' => 'New Vegas, Haarlem\'s first vegan restaurant, offers creative twists on familiar dishes using seasonal, plant-based ingredients. Known for its 3D-printed steak and innovative menu, it provides a unique dining experience with vegan sides and bites in a casual atmosphere, perfect for adventurous food lovers.',
        'cuisine' => 'Vegan',
        'image' => '/assets/img/restaurants/newvegas.png',
        'icon' => 'icon5.png',
    ],
    [
        'name' => 'Grand Cafe Brinkmann',
        'rating' => 3,
        'description' => 'Grand Café Brinkmann offers a cozy and welcoming atmosphere in Haarlem, where guests can enjoy delicious drinks and a relaxed ambiance. With a varied menu and the option to rent rooms for special events, it is the perfect place for both a casual meal and a special occasion.',
        'cuisine' => 'Dutch, European, Modern',
        'image' => '/assets/img/restaurants/brinkmann.png',
        'icon' => 'icon6.png',
    ],
    [
        'name' => 'Urban Frenchy Bistro Toujours',
        'rating' => 3,
        'description' => 'Toujours in Haarlem offers a luxurious private dining experience with a menu featuring truffle, wagyu, caviar and sushi. Enjoy cocktails, wine, and beer on the cozy terrace, perfect for an unforgettable meal. Open daily, Toujours is the ideal spot for refined dining with family or friends.',
        'cuisine' => 'Dutch, fish and seafood, European',
        'image' => '/assets/img/restaurants/toujours.png',
        'icon' => 'icon7.png',
    ],
];
?>

<!-- Festival Information Banner -->
<div class="container mt-5">
    <div class="info-box">
        <p><strong>During the Festival, participating restaurants offer a special menu</strong> at a reduced price, <br> including a <strong>€10 reservation fee</strong> (deducted from the final bill).</p>
        <p>A children's price is also available.</p>
        <p>Each restaurant hosts 2-3 sessions lasting <strong>1.5 to 2 hours</strong>, <br> where tables are reserved exclusively for festival guests.</p>
        <p>Your meal is carefully prepared <strong>during the session</strong> to ensure a smooth and enjoyable experience.</p>
        <p>Seats are limited, so be sure to check <br><strong>availability and the type</strong> of cuisine each restaurant offers before booking.</p>
    </div>
</div>

<div class="container">
    <h2 class="text-center restaurants-title">Our Restaurants</h2>
    <div class="restaurant-container">
        <?php foreach ($restaurants as $key => $restaurant) : 
            $isEven = $key % 2 == 0;
        ?>
            <div class="restaurant-row <?= $isEven ? 'even-row' : 'odd-row' ?>">
                <!-- Icon section outside the card -->
                <div class="icon-wrapper <?= $isEven ? 'icon-left' : 'icon-right' ?>">
                    <div class="restaurant-icon">
                        <img src="/assets/img/icons/<?= $restaurant['icon']; ?>" alt="<?= $restaurant['name']; ?> Icon">
                    </div>
                </div>
                
                <!-- Restaurant card -->
                <div class="restaurant-card <?= $isEven ? 'card-right' : 'card-left' ?>">
                    <?php if ($isEven) : ?>
                        <div class="restaurant-image">
                            <div class="image-wrapper">
                                <img src="<?= $restaurant['image']; ?>" alt="<?= $restaurant['name']; ?>">
                            </div>
                        </div>
                        <div class="restaurant-content">
                            <div class="restaurant-header">
                                <h3><?= $restaurant['name']; ?></h3>
                                <div class="stars">
                                    <?php for ($i = 0; $i < $restaurant['rating']; $i++) : ?>
                                        <span>&#9733;</span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p><?= $restaurant['description']; ?></p>
                            <div class="restaurant-footer">
                                <div class="cuisine-container">
                                    <div class="cuisine">
                                        <em><?= $restaurant['cuisine']; ?></em>
                                    </div>
                                </div>
                                <div class="button-container">
                                    <button class="btn btn-primary visit-btn">Visit <i class="arrow-icon"></i></button>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="restaurant-content">
                            <div class="restaurant-header">
                                <div class="stars">
                                    <?php for ($i = 0; $i < $restaurant['rating']; $i++) : ?>
                                        <span>&#9733;</span>
                                    <?php endfor; ?>
                                </div>
                                <h3><?= $restaurant['name']; ?></h3>
                            </div>
                            <p><?= $restaurant['description']; ?></p>
                            <div class="restaurant-footer">
                                <div class="cuisine-container">
                                    <div class="cuisine">
                                        <em><?= $restaurant['cuisine']; ?></em>
                                    </div>
                                </div>
                                <div class="button-container">
                                    <button class="btn btn-primary visit-btn">Visit <i class="arrow-icon"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="restaurant-image">
                            <div class="image-wrapper">
                                <img src="<?= $restaurant['image']; ?>" alt="<?= $restaurant['name']; ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
/* Existing Styles */

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
}

.mt-5 {
    margin-top: 2rem;
}

/* Info Box */
.info-box {
    background-color: #2c4d69;
    color: white;
    padding: 20px 30px;
    margin-bottom: 50px;
    text-align: center;
    border-radius: 10px;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.info-box p {
    margin: 10px 0;
}

/* Restaurant Section */
.restaurants-title {
    margin: 50px 0 60px;
    font-size: 28px;
    font-weight: bold;
}

.restaurant-container {
    display: flex;
    flex-direction: column;
    gap: 40px;
    margin-bottom: 60px;
    position: relative;
}

.restaurant-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 30px;
    margin-bottom: 30px;
}

/* Icon styles */
.icon-wrapper {
    display: flex;
    align-items: center;
}

.restaurant-icon {
    width: 150px;
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    box-shadow: none;
    background: transparent;
    overflow: visible;
}

.restaurant-icon img {
    width: 100%;
    height: auto;
    object-fit: contain;
}

/* Restaurant card styles */
.restaurant-card {
    display: flex;
    background-color: #2c4d69;
    color: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%;
}

.restaurant-image {
    flex: 0 0 130px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 20px;
}

.image-wrapper {
    width: 100%;
    height: 130px;
    background-color: white;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5px;
}

.restaurant-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    margin: 0 10px;
}

.restaurant-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.restaurant-header h3 {
    margin: 0;
    font-size: 22px;
}

.stars {
    display: flex;
}

.stars span {
    color: gold;
    font-size: 24px;
}

.restaurant-footer {
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}

.cuisine-container {
    display: flex;
    justify-content: flex-start;
}

.cuisine {
    font-style: italic;
    color: white;
    background-color: #226C92;
    padding: 5px 10px;
    border-radius: 4px;
    display: inline-block;
}

.button-container {
    display: flex;
}

.visit-btn {
    background-color: #E6A640;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    color: black;
    font-weight: bold;
    transition: background-color 0.3s;
    display: flex;
    align-items: center;
    gap: 5px;
}

.visit-btn:hover {
    background-color: #CC9439;
    color: black;
}
.arrow-icon {
    display: inline-block;
    width: 14px;
    height: 14px;
    background-image: url('/assets/img/icons/arrow-right.ico');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
}

/* Alternating left-right icons and content */
.even-row {
    flex-direction: row-reverse;
}

.odd-row {
    flex-direction: row;
}

/* Even row (right-positioned boxes) specific styles */
.even-row .restaurant-footer {
    flex-direction: row;
}

.even-row .button-container {
    justify-content: flex-start;
}

.even-row .cuisine-container {
    justify-content: flex-end;
}

/* Odd row (left-positioned boxes) specific styles */
.odd-row .restaurant-content {
    text-align: right;
}

.odd-row .restaurant-footer {
    flex-direction: row-reverse;
}

.odd-row .button-container {
    justify-content: flex-start;
}

.odd-row .cuisine-container {
    justify-content: flex-end;
}

/* Restaurant logo images */
.restaurant-image img {
    width: 100%;
    height: auto;
    object-fit: contain;
}

@media (max-width: 768px) {
    .restaurant-card {
        flex-direction: column;
        width: 100%;
        margin: 0;
    }
    
    .restaurant-row {
        justify-content: center;
        flex-direction: column;
    }

    .restaurant-image {
        margin: 0 auto 15px;
    }

    .restaurant-content {
        text-align: center !important;
        margin: 0;
    }

    .restaurant-footer {
        flex-direction: column !important;
        align-items: center;
        gap: 15px;
    }

    .button-container, 
    .cuisine-container {
        width: 100%;
        justify-content: center !important;
    }
    
    .even-row .restaurant-footer,
    .odd-row .restaurant-footer {
        flex-direction: column !important;
    }
    
    .even-row .button-container,
    .odd-row .button-container,
    .even-row .cuisine-container,
    .odd-row .cuisine-container {
        justify-content: center !important;
    }
}
</style>
