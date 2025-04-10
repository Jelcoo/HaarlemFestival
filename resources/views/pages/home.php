<?php

$events = [
    [
        'name' => 'DANCE!',
        'short_description' => 'Do you like EDM, Techno, etc. Then your in the right place!',
        'description' => 'Feel the beat and lose yourself in the rhythm at DANCE!, where the world’s top DJs take center stage in Haarlem’s most iconic venues. From high-energy back-to-back sessions to intimate experimental sets, this event promises an electrifying mix of house, techno, and trance. Happening July 25th to 27th, DANCE! is your ticket to a dynamic, joy-filled weekend. Let’s make it a party to remember!',
        'slider_image' => '/assets/img/events/slider/dance.png',
        'image' => '/assets/img/events/dance.png',
        'button_text' => 'Visit',
        'button_link' => '/dance',
    ],
    [
        'name' => 'Yummy!',
        'short_description' => 'Do you like food, original dishes and more.',
        'description' => 'Treat your taste buds to a culinary adventure with Yummy!, a celebration of Haarlem’s vibrant dining scene. From July 24th to 27th, discover exclusive festival menus crafted by the city’s top restaurants, all at special prices. Whether you’re a seasoned foodie or just looking to indulge, this event promises delightful flavors and unforgettable dining experiences. Make your reservations through the festival website and get ready to savor Haarlem, one bite at a time!',
        'slider_image' => '/assets/img/events/slider/yummy.png',
        'image' => '/assets/img/events/yummy.png',
        'button_text' => 'Visit',
        'button_link' => '/yummy',
    ],
    [
        'name' => 'A stroll through history',
        'short_description' => 'Do you like to know more about the history of Haarlem.',
        'description' => 'Step back in time with A Stroll Through History, an immersive guided tour through Haarlem’s most historic landmarks. From the majestic Church of St. Bavo to the picturesque Hofjes, this 2.5-hour journey showcases the city’s rich heritage and hidden gems. Perfect for history enthusiasts, the tour runs July 24th to 27th and includes a refreshing break at Jopenkerk. Come and uncover the stories that shaped Haarlem!',
        'slider_image' => '/assets/img/events/slider/history.png',
        'image' => '/assets/img/events/history.png',
        'button_text' => 'Visit',
        'button_link' => '/history',
    ],
    [
        'name' => 'Magic@Tylers',
        'short_description' => 'Do you like museums and want to solve a mystery?',
        'description' => 'Unleash your curiosity with Magic@Teylers, an interactive experience at the renowned Teylers Museum. From July 24th to 27th, kids and families can solve puzzles, conduct experiments, and uncover the Secret of Professor Teyler through fun challenges. Plus, enjoy family-friendly performances like "The Lorentz Formula." Science meets adventure in this captivating event designed to inspire wonder and ignite imaginations!',
        'slider_image' => '/assets/img/events/slider/teylers.png',
        'image' => '/assets/img/events/teylers.png',
        'button_text' => 'Visit',
        'button_link' => '/magic',
    ],
];
?>
<link rel="stylesheet" href="/assets/css/home.css">
<div class="container-fluid p-0">
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php foreach ($events as $event) { ?>
                <div class="swiper-slide" style="background-image: url('<?php echo $event['slider_image']; ?>');">
                    <div class="slide-content">
                        <h2><?php echo $event['name']; ?></h2>
                        <p><?php echo $event['short_description']; ?></p>
                        <a href="<?php echo $event['button_link']; ?>" class="btn btn-primary"><i
                                class="fa fa-arrow-right me-2"></i><?php echo $event['button_text']; ?></a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <div class="swiper-pagination"></div>
    </div>
</div>

<h2 class="text-center mt-5">Welcome to The Festival 2025!</h2>
<div class="container">
    <p>
        Experience the vibrant culture, history, and creativity of Haarlem in a celebration like no other! From July
        24th to July 27th, 2025, the heart of Haarlem comes alive with music, food, dance, history, and storytelling.
        The Festival offers something for everyone—whether you're a jazz enthusiast, a foodie, a history buff, or a
        family looking for fun-filled adventures.
        <br />
        Here's a glimpse of what's in store:
    <ul>
        <li>Yummy!: Savor exclusive festival menus from Haarlem's best restaurants at unbeatable prices.</li>
        <li>DANCE!: Party to world-class DJs in unique locations with back-to-back sessions that will keep you on your
            feet.</li>
        <li>A Stroll Through History: Discover the rich history of Haarlem with guided tours through iconic landmarks.
        </li>
        <li>Magic@Teylers: Engage in thrilling science challenges and interactive exhibits perfect for kids and
            families.</li>
    </ul>
    Join us for this extraordinary journey through the sights, sounds, and flavors of Haarlem. Whether you're exploring
    with family, meeting friends, or embarking on a solo adventure, The Festival promises unforgettable memories.
    <br />
    Plan your visit now, and let the festivities begin!
    </p>
</div>

<div class="container-fluid px-0">
    <?php $count = 0; ?>
    <?php foreach ($events as $event) { ?>
        <?php if ($count % 2 == 0) { ?>
            <div class="row g-0">
            <?php } ?>

            <div class="col-md-6 col-lg-6 event-card" style="background-image: url('<?php echo $event['image']; ?>');">
                <div class="overlay">
                    <div>
                        <h2><?php echo $event['name']; ?></h2>
                        <p><?php echo $event['description']; ?></p>
                    </div>
                    <div>
                        <a href="<?php echo $event['button_link']; ?>" class="btn btn-custom-yellow">
                            <i class="fa fa-arrow-right me-2"></i><?php echo $event['button_text']; ?>
                        </a>
                    </div>
                </div>
            </div>

            <?php ++$count; ?>
            <?php if ($count % 2 == 0 || $count == count($events)) { ?>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<h2 class="text-center mt-5">Schedule</h2>
<div class="container schedule-container">
    <div class="row">
        <div class="col-md-3 schedule-day">
            <div class="schedule-day-header">Thursday 24 July</div>
            <div class="event-bar">Haarlem Jazz</div>
        </div>

        <div class="col-md-3 schedule-day">
            <div class="schedule-day-header">Friday 25 July</div>
            <div class="event-bar">Yummy!</div>
            <div class="event-bar">DANCE!</div>
        </div>

        <div class="col-md-3 schedule-day">
            <div class="schedule-day-header">Saturday 26 July</div>
            <div class="event-bar">A Stroll through History</div>
            <div class="event-bar">Magic@Teylers</div>
        </div>

        <div class="col-md-3 schedule-day">
            <div class="schedule-day-header">Sunday 27 July</div>
            <div class="event-bar">Stories in Haarlem</div>
        </div>
    </div>
</div>

<h2 class="text-center mt-5">Map</h2>
<div class="container map-container mb-5">
    <div id="map" class="h-100"></div>
</div>

<script>
    const swiper = new Swiper('.swiper', {
        direction: 'horizontal',
        loop: true,

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

    const map = L.map('map').setView([52.39330619537042, 4.635887145996095], 14);

    L.tileLayer(`https://basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}@2x.png`, {
        minZoom: 12,
        maxZoom: 18,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    const locations = <?php echo json_encode($locations); ?>;

    locations.forEach(location => {
        const coords = location.coordinates.split(',');
        L.marker(coords).addTo(map)
            .bindPopup(`
            <h4>${location.name}</h4>
            <p>${location.preview_description}</p>
            <p><em>Address: ${location.address}</em></p>
        `);
    });

    setTimeout(() => map.invalidateSize(), 100);
</script>