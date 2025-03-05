<?php

$locations = [
    [
        'name' => 'Grote Markt',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/grotemarkt.png',
    ],
    [
        'name' => 'De Hallen',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/dehallen.png',
    ],
    [
        'name' => 'Proveniershof',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/proveniershof.png',
    ],
    [
        'name' => 'Jopenkerk',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/jopenkerk-2.png',
    ],
    [
        'name' => 'Waalse Kerk Haarlem',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/waalsekerk.png',
    ],
    [
        'name' => 'Molen de Adriaan',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/molendeadriaan.png',
    ],
    [
        'name' => 'Amsterdamse Poort',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/amsterdamsepoort.png',
    ],
    [
        'name' => 'Hof van Bakenes',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/hofvanbakenes.png',
    ],
    [
        'name' => 'Church of St. Bavo',
        'lng' => 0,
        'lat' => 0,
        'description' => '',
        'address' => '',
        'image' => '/assets/img/locations/stbavo.png',
    ],
];

$schedules = [
    [
        'date' => 'Thursday July 24',
        'location' => 'Bavo Church',
        'seats_per_tour' => 12,
        'prices' => [
            'single' => 17.50,
            'family' => 60.00,
        ],
        'guides' => [
            [
                'language' => 'Dutch',
                'names' => ['Jan-Willem'],
            ],
            [
                'language' => 'English',
                'names' => ['Frederic'],
            ],
        ],
        'start' => [
            [
                'time' => '10:00',
                'tours' => [
                    'Dutch' => 1,
                    'English' => 1,
                ],
            ],
            [
                'time' => '13:00',
                'tours' => [
                    'Dutch' => 1,
                    'English' => 1,
                ],
            ],
            [
                'time' => '16:00',
                'tours' => [
                    'Dutch' => 1,
                    'English' => 1,
                ],
            ],
        ],
    ],
    [
        'date' => 'Friday July 25',
        'location' => 'Bavo Church',
        'seats_per_tour' => 12,
        'prices' => [
            'single' => 17.50,
            'family' => 60.00,
        ],
        'guides' => [
            [
                'language' => 'Dutch',
                'names' => ['Annet'],
            ],
            [
                'language' => 'English',
                'names' => ['William'],
            ],
            [
                'language' => 'Chinese',
                'names' => ['Kim'],
            ],
        ],
        'start' => [
            [
                'time' => '10:00',
                'tours' => [
                    'Dutch' => 1,
                    'English' => 1,
                ],
            ],
            [
                'time' => '13:00',
                'tours' => [
                    'Dutch' => 1,
                    'English' => 1,
                    'Chinese' => 1,
                ],
            ],
            [
                'time' => '16:00',
                'tours' => [
                    'Dutch' => 1,
                    'English' => 1,
                    'Chinese' => 1,
                ],
            ],
        ],
    ],
    [
        'date' => 'Saturday July 26',
        'location' => 'Bavo Church',
        'seats_per_tour' => 12,
        'prices' => [
            'single' => 17.50,
            'family' => 60.00,
        ],
        'guides' => [
            [
                'language' => 'Dutch',
                'names' => ['Annet', 'Jan-Willem'],
            ],
            [
                'language' => 'English',
                'names' => ['Frederic', 'William'],
            ],
            [
                'language' => 'Chinese',
                'names' => ['Kim'],
            ],
        ],
        'start' => [
            [
                'time' => '10:00',
                'tours' => [
                    'Dutch' => 2,
                    'English' => 2,
                ],
            ],
            [
                'time' => '13:00',
                'tours' => [
                    'Dutch' => 2,
                    'English' => 2,
                    'Chinese' => 1,
                ],
            ],
            [
                'time' => '16:00',
                'tours' => [
                    'Dutch' => 1,
                    'English' => 1,
                    'Chinese' => 1,
                ],
            ],
        ],
    ],
    [
        'date' => 'Sunday July 27',
        'location' => 'Bavo Church',
        'seats_per_tour' => 12,
        'prices' => [
            'single' => 17.50,
            'family' => 60.00,
        ],
        'guides' => [
            [
                'language' => 'Dutch',
                'names' => ['Annet', 'Jan-Willem', 'Lisa'],
            ],
            [
                'language' => 'English',
                'names' => ['Deirdre', 'Frederic', 'William'],
            ],
            [
                'language' => 'Chinese',
                'names' => ['Kim', 'Susan'],
            ],
        ],
        'start' => [
            [
                'time' => '10:00',
                'tours' => [
                    'Dutch' => 2,
                    'English' => 2,
                    'Chinese' => 1,
                ],
            ],
            [
                'time' => '13:00',
                'tours' => [
                    'Dutch' => 3,
                    'English' => 3,
                    'Chinese' => 2,
                ],
            ],
            [
                'time' => '16:00',
                'tours' => [
                    'Dutch' => 1,
                    'English' => 1,
                ],
            ],
        ],
    ],
];
?>

<?php
$header_name = 'A stroll through History';
$header_description = 'Discover the rich history of Haarlem on a captivating guided walking tour. From the grandeur of the Church of St. Bavo to the charm of the Amsterdamse Poort, A Stroll Through History invites you to explore the city\'s most iconic landmarks. This 2.5-hour tour, complete with a refreshing break, is your chance to step into Haarlem’s past and uncover its stories of resilience, culture, and innovation.';
$header_dates = 'July 24 - 27, 2025';
$header_image = '/assets/img/events/slider/history.png';

include_once __DIR__ . '/../components/header.php';
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

<h2 class="text-center mt-5">Map</h2>
<div class="container map-container">
    <div id="map" class="h-100"></div>
</div>

<h2 class="text-center mt-5">Schedule</h2>
<div class="container p-0">
    <?php $scheduleCount = 0; ?>
    <?php foreach ($schedules as $schedule) { ?>
        <?php if ($scheduleCount % 4 == 0) { ?>
            <div class="row g-0 gap-2">
        <?php } ?>

        <div class="tour-ticket-card card shadow-sm">
            <div class="card-header text-center">
                <h5 class="card-title mb-0"><?php echo $schedule['date']; ?></h5>
            </div>
            <div class="card-body">
                <div class="tour-detail">
                <div class="row mb-2">
                    <div class="col-5 text-muted">Start Location</div>
                    <div class="col-7 text-end"><?php echo $schedule['location']; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-muted">Seats per Tour</div>
                    <div class="col-7 text-end"><?php echo $schedule['seats_per_tour']; ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-5 text-muted">Prices</div>
                    <div class="col-7 text-end">
                        <div>Single: €<?php echo $schedule['prices']['single']; ?></div>
                        <div>Family: €<?php echo $schedule['prices']['family']; ?> *</div>
                    </div>
                </div>

                <div class="guides-section mb-3">
                    <h6 class="text-center mb-2">Guides</h6>
                    <?php foreach ($schedule['guides'] as $guide) { ?>
                        <div class="row mb-1">
                            <div class="col-5 text-muted"><?php echo $guide['language']; ?></div>
                            <div class="col-7 text-end"><?php echo implode(', ', $guide['names']); ?></div>
                        </div>
                    <?php } ?>
                </div>

                <div class="starting-times">
                    <h6 class="text-center mb-2">Starting Time</h6>
                    <div class="time-slots">
                        <?php foreach ($schedule['start'] as $start) { ?>
                            <div class="row mb-1">
                                <div class="col-6"><?php echo $start['time']; ?></div>
                                <div class="col-6 text-end">
                                    <?php
                                        $tours = array_map(function ($lang, $count) {
                                            return "{$count}x $lang";
                                        }, array_keys($start['tours']), array_values($start['tours']));
                            echo implode('<br>', $tours);
                            ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-custom-yellow w-100"><i class="fa-solid fa-ticket"></i> Buy Ticket</button>
            </div>
        </div>

        <?php ++$scheduleCount; ?>
        <?php if ($scheduleCount % 4 == 0 || $scheduleCount == count($locations)) { ?>
            </div>
        <?php } ?>
    <?php } ?>
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

    const map = L.map('map').setView([52.39330619537042, 4.635887145996095], 14);

    L.tileLayer(`https://basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}@2x.png`, {
        minZoom: 12,
        maxZoom: 18,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    const locations = <?php echo json_encode($locations); ?>;

    locations.forEach(location => {
        L.marker([location.lat, location.lng]).addTo(map)
            .bindPopup(`
                <h4>${location.name}</h4>
                <p>${location.description}</p>
                <p><em>Address: ${location.address}</em></p>
            `);
    });

    setTimeout(() => map.invalidateSize(), 100);
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

.tour-ticket-card {
    max-width: 300px;
    border-radius: 10px;
    overflow: hidden;
}

.tour-ticket-card .card-header {
    background-color: #f8f9fa;
    padding: 10px;
}

.tour-ticket-card .card-body {
    padding: 15px;
}

.tour-ticket-card .guides-section,
.tour-ticket-card .starting-times {
    border-top: 1px solid #e9ecef;
    padding-top: 10px;
    margin-top: 10px;
}
</style>
