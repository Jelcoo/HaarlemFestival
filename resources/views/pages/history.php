<?php

$locations = [
    [
        'name' => 'Grote Markt',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/grotemarkt.png'
    ],
    [
        'name' => 'De Hallen',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/dehallen.png'
    ],
    [
        'name' => 'Proveniershof',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/proveniershof.png'
    ],
    [
        'name' => 'Jopenkerk',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/jopenkerk-2.png'
    ],
    [
        'name' => 'Waalse Kerk Haarlem',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/waalsekerk.png'
    ],
    [
        'name' => 'Molen de Adriaan',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/molendeadriaan.png'
    ],
    [
        'name' => 'Amsterdamse Poort',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/amsterdamsepoort.png'
    ],
    [
        'name' => 'Hof van Bakenes',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/hofvanbakenes.png'
    ],
    [
        'name' => 'Church of St. Bavo',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/stbavo.png'
    ],
];
?>

<?php
$header_name = 'A stroll through History';
$header_description = 'Discover the rich history of Haarlem on a captivating guided walking tour. From the grandeur of the Church of St. Bavo to the charm of the Amsterdamse Poort, A Stroll Through History invites you to explore the city\'s most iconic landmarks. This 2.5-hour tour, complete with a refreshing break, is your chance to step into Haarlem’s past and uncover its stories of resilience, culture, and innovation.';
$header_dates = 'July 24 - 27, 2025';
$header_image = '/assets/img/events/slider/history.png';

include_once __DIR__.'/../components/header.php';
?>

<h2 class="text-center mt-5">Locations</h2>
<div class="container-fluid p-0">
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php foreach ($locations as $location) { ?>
                <div class="swiper-slide" style="background-image: url('<?php echo $location['image']; ?>');">
                    <div class="slide-content">
                        <h2><?php echo $location['name']; ?></h2>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <div class="swiper-pagination"></div>
    </div>
</div>

<script>
    const swiper = new Swiper('.swiper', {
        direction: 'horizontal',
        loop: true,
        slidesPerView: 3,

        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },

        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        }
    });
</script>

<style>
.swiper {
    width: 100%;
    height: 500px;
}
.swiper-slide {
    position: relative;
    background-repeat: no-repeat;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
}
.slide-content {
    text-align: center;
    color: white;
    background: rgba(0,0,0,0.5);
    padding: 20px;
    border-radius: 10px;
    max-width: 80%;
}
</style>
