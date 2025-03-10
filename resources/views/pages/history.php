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
                    'Dutch' => [1],
                    'English' => [2],
                ],
            ],
            [
                'time' => '13:00',
                'tours' => [
                    'Dutch' => [3],
                    'English' => [4],
                ],
            ],
            [
                'time' => '16:00',
                'tours' => [
                    'Dutch' => [6],
                    'English' => [5],
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
                    'Dutch' => [7],
                    'English' => [10],
                ],
            ],
            [
                'time' => '13:00',
                'tours' => [
                    'Dutch' => [8],
                    'English' => [11],
                    'Chinese' => [13],
                ],
            ],
            [
                'time' => '16:00',
                'tours' => [
                    'Dutch' => [9],
                    'English' => [12],
                    'Chinese' => [14],
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
                    'Dutch' => [15, 16],
                    'English' => [20, 21],
                ],
            ],
            [
                'time' => '13:00',
                'tours' => [
                    'Dutch' => [17, 18],
                    'English' => [22, 23],
                    'Chinese' => [25],
                ],
            ],
            [
                'time' => '16:00',
                'tours' => [
                    'Dutch' => [19],
                    'English' => [24],
                    'Chinese' => [26],
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
                    'Dutch' => [27, 28],
                    'English' => [33, 34],
                    'Chinese' => [39],
                ],
            ],
            [
                'time' => '13:00',
                'tours' => [
                    'Dutch' => [29, 30, 31],
                    'English' => [35, 36, 37],
                    'Chinese' => [40, 41],
                ],
            ],
            [
                'time' => '16:00',
                'tours' => [
                    'Dutch' => [32],
                    'English' => [38],
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
                                                return count($count) . "x $lang";
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
                    <button class="btn btn-custom-yellow w-100"
                        data-price-family="<?php echo $schedule['prices']['family']; ?>"
                        data-price-single="<?php echo $schedule['prices']['single']; ?>" data-tours="<?php foreach ($schedule['start'] as $start) {
                            // Start with the time followed by a dot
                            echo $start['time'] . '.';

                            $langStrings = [];
                            // Loop through each language and its array of tour IDs
                            foreach ($start['tours'] as $lang => $ids) {
                                // Build a string in the format "Language:id1,id2"
                                $langStrings[] = $lang . ':' . implode(',', $ids);
                            }

                            // Join all language strings with a "?" delimiter, and end with a semicolon
                            echo implode('?', $langStrings) . ';';
                        } ?>" onclick="parseData()"><i class="fa-solid fa-ticket"></i>
                        Buy Ticket</button>
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

    function parseData() {
        let eventData = event.target.dataset;
        if (eventData.tours === undefined) {
            eventData = event.target.parentElement.dataset;
        }

        let dataStr = eventData.tours;
        // Spliting the different timesschedules
        const entries = dataStr.split(";").filter(entry => entry.trim() !== "");

        const schedule = entries.map(entry => {
            // Split by . to get the time
            const [time, toursStr] = entry.split(".");

            // Split the tours string by "?" to get each language entry
            const tourEntries = toursStr.split("?");

            // Loop through each language to build the object
            const tours = tourEntries.reduce((acc, tourEntry) => {
                const [language, idsStr] = tourEntry.split(":");
                if (language && idsStr) {
                    // Convert ids to an array of numbers
                    acc[language] = idsStr.split(",").map(id => parseInt(id, 10));
                }
                return acc;
            }, {});

            return { time, tours };
        });

        console.log(schedule);
    }
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
