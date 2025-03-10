<?php

$artists = [
    [
        'name' => 'Hardwell',
        'image' => '/assets/img/artists/hardwell.png',
        'description' => 'A powerhouse in the electronic dance music world, Hardwell is known for his explosive live performances and chart-topping tracks. Hailing from Breda, Netherlands, this superstar DJ and producer has dominated global stages with hits like "Spaceman" and "Apollo". Hardwell\'s blend of big-room house and progressive beats makes him a fan favorite.',
        'link' => '/dance/hardwell',
    ],
    [
        'name' => 'Armin van Buuren',
        'image' => '/assets/img/artists/armin.png',
        'description' => 'A legend in trance music, Armin van Buuren has been at the forefront of the EDM scene for decades. With five-time DJ Mag\'s "World’s No. 1 DJ" titles and iconic tracks like "This Is What It Feels Like", Armin has captivated audiences worldwide. His A State of Trance radio show is a lifeline for trance enthusiasts everywhere.',
        'link' => '/dance/armin',
    ],
    [
        'name' => 'Martin Garrix',
        'image' => '/assets/img/artists/martin.png',
        'description' => 'Known for his breakout hit "Animals", Martin Garrix became a global sensation as a teenager. Now a staple in the EDM world, the Dutch producer is celebrated for his infectious melodies and collaborations with artists like Dua Lipa, Bebe Rexha, and Khalid. Martin’s energy and passion light up every stage he touches.',
        'link' => '/dance/martin',
    ],
    [
        'name' => 'Tiësto',
        'image' => '/assets/img/artists/tiesto.png',
        'description' => 'The "Godfather of EDM," Tiësto has redefined the electronic music landscape. From trance beginnings to becoming a global pop-crossover sensation with hits like "Red Lights" and "The Business", Tiësto’s evolution is legendary. His ability to stay at the forefront of the scene makes him a timeless icon.',
        'link' => '/dance/tiesto',
    ],
    [
        'name' => 'Nicky Romero',
        'image' => '/assets/img/artists/nicky.png',
        'description' => 'A master of progressive house, Nicky Romero burst onto the scene with hits like "Toulouse" and "I Could Be the One" with Avicii. As a DJ, producer, and label head of Protocol Recordings, he’s recognized for his dynamic sound and mentorship of upcoming artists. His sets are a journey through emotion and rhythm.',
        'link' => '/dance/nicky',
    ],
    [
        'name' => 'Afrojack',
        'image' => '/assets/img/artists/afrojack.png',
        'description' => 'Afrojack is a Grammy-winning DJ and producer renowned for his signature Dutch house sound. Known for tracks like "Take Over Control" and "Ten Feet Tall", he’s a regular at major festivals worldwide. Afrojack’s collaborations with artists such as Beyoncé and David Guetta underscore his versatility and influence.',
        'link' => '/dance/afrojack',
    ],
];

$locations = [
    [
        'name' => 'Slachthuis',
        'image' => '/assets/img/locations/slachthuis.jpg',
        'description' => 'Once an industrial slaughterhouse, Slachthuis has been transformed into a dynamic cultural hotspot. Known for its edgy and raw atmosphere, this venue is a favorite for high-energy performances and underground vibes. Its unique architecture creates an unforgettable experience for music lovers.',
        'address' => 'Rockplein 6, 2033 KK Haarlem',
    ],
    [
        'name' => 'Caprera Openluchttheater',
        'image' => '/assets/img/locations/caprera.jpg',
        'description' => 'Nestled amidst lush greenery, Caprera Openluchttheater is an enchanting open-air venue perfect for unforgettable performances under the stars. Its natural acoustics and scenic beauty make it an iconic spot for electronic music and cultural events alike.',
        'address' => 'Hoge Duin en Daalseweg 2, 2061 AG Bloemendaal',
    ],
    [
        'name' => 'Jopenkerk',
        'image' => '/assets/img/locations/jopenkerk.jpg',
        'description' => 'A stunning fusion of history and modernity, Jopenkerk is a former church turned brewery and event space. With its vibrant atmosphere and excellent acoustics, this venue offers a unique blend of sacred architecture and pulsating beats.',
        'address' => 'Gedempte Voldersgracht 2, 2011 WD Haarlem',
    ],
    [
        'name' => 'Lichtfabriek',
        'image' => '/assets/img/locations/lichtfabriek.jpg',
        'description' => 'Located in a historic power station, Lichtfabriek exudes industrial charm and creative energy. Its spacious interiors and captivating ambiance make it an ideal venue for large-scale performances and immersive musical experiences.',
        'address' => 'Minckelersweg 2, 2031 EM Haarlem',
    ],
    [
        'name' => 'Puncher Comedy Club',
        'image' => '/assets/img/locations/puncher.jpg',
        'description' => 'Situated in the heart of Haarlem, Puncher Comedy Club combines a cozy setting with electric energy. While known for its comedy, it transforms into an intimate and vibrant space for special performances during the festival.',
        'address' => 'Grote Markt 10, 2011 RD Haarlem',
    ],
    [
        'name' => 'XO the club',
        'image' => '/assets/img/locations/xo.jpg',
        'description' => 'XO the Club is a chic and modern nightlife destination where style meets sound. Its sleek interiors and state-of-the-art lighting set the stage for a night of high-energy dance and unforgettable moments.',
        'address' => 'Grote Markt 8, 2011 RD Haarlem',
    ],
];

$schedules = [
    [
        'date' => 'Friday July 25',
        'rows' => [
            [
                'start' => '20:00',
                'venue' => 'Lichtfabriek',
                'artists' => [
                    'Nicky Romero',
                    'Afrojack',
                ],
                'session' => 'Back2Back',
                'duration' => 360,
                'tickets_available' => 1500,
                'price' => 75.00,
            ],
            [
                'start' => '22:00',
                'venue' => 'Slachthuis',
                'artists' => [
                    'Tiësto',
                ],
                'session' => 'Club',
                'duration' => 90,
                'tickets_available' => 200,
                'price' => 60.00,
            ],
            [
                'start' => '20:00',
                'venue' => 'Jopenwerk',
                'artists' => [
                    'Hardwell',
                ],
                'session' => 'Club',
                'duration' => 90,
                'tickets_available' => 300,
                'price' => 60.00,
            ],
            [
                'start' => '20:00',
                'venue' => 'XO the club',
                'artists' => [
                    'Armin van Buuren',
                ],
                'session' => 'Club',
                'duration' => 90,
                'tickets_available' => 200,
                'price' => 60.00,
            ],
            [
                'start' => '20:00',
                'venue' => 'Puncher comedy club',
                'artists' => [
                    'Martin Garrix',
                ],
                'session' => 'Club',
                'duration' => 90,
                'tickets_available' => 200,
                'price' => 60.00,
            ],
        ],
        'notes' => [
            '* All-Access pass for this day €150,00, All-Access pass for Fri, Sat,Sun: €250,00.<br>The capacity of the Club sessions is very limited. Availability for All-Access pas holders can not be garanteed due to safety regulations.<br>Tickets available represents total capacity. (90% is sold as single tickets. 10% of total capacity is left for Walk ins/All-Acces passholders.',
        ],
    ],
    [
        'date' => 'Saturday July 26',
        'rows' => [
            [
                'start' => '14:00',
                'venue' => 'Caprera Openluchttheater',
                'artists' => [
                    'Hardwell',
                    'Martin Garrix',
                    'Armin van Buuren',
                ],
                'session' => 'Back2Back',
                'duration' => 540,
                'tickets_available' => 2000,
                'price' => 110.00,
            ],
            [
                'start' => '22:00',
                'venue' => 'Jopenwerk',
                'artists' => [
                    'Afrojack',
                ],
                'session' => 'Club',
                'duration' => 90,
                'tickets_available' => 300,
                'price' => 60.00,
            ],
            [
                'start' => '21:00',
                'venue' => 'Lichtfabriek',
                'artists' => [
                    'Tiësto',
                ],
                'session' => 'TiëstoWorld',
                'duration' => 240,
                'tickets_available' => 1500,
                'price' => 75.00,
            ],
            [
                'start' => '23:00',
                'venue' => 'Slachthuis',
                'artists' => [
                    'Nicky Romero',
                ],
                'session' => 'Club',
                'duration' => 90,
                'tickets_available' => 200,
                'price' => 60.00,
            ],
        ],
        'notes' => [
            '* All-Access pass for this day €150,00, All-Access pass for Fri, Sat,Sun: €250,00.<br>The capacity of the Club sessions is very limited. Availability for All-Access pas holders can not be garanteed due to safety regulations.<br>Tickets available represents total capacity. (90% is sold as single tickets. 10% of total capacity is left for Walk ins/All-Acces passholders.',
            '** TiëstoWorld is a special session spanning his careers work. There will also be some special guests.',
        ],
    ],
    [
        'date' => 'Sunday July 27',
        'rows' => [
            [
                'start' => '14:00',
                'venue' => 'Caprera Openluchttheater',
                'artists' => [
                    'Afrojack',
                    'Tiësto',
                    'Nicky Romero',
                ],
                'session' => 'Back2Back',
                'duration' => 540,
                'tickets_available' => 2000,
                'price' => 110.00,
            ],
            [
                'start' => '19:00',
                'venue' => 'Jopenwerk',
                'artists' => [
                    'Armin van Buuren',
                ],
                'session' => 'Club',
                'duration' => 90,
                'tickets_available' => 300,
                'price' => 60.00,
            ],
            [
                'start' => '21:00',
                'venue' => 'XO the Club',
                'artists' => [
                    'Hardwell',
                ],
                'session' => 'Club',
                'duration' => 90,
                'tickets_available' => 1500,
                'price' => 90.00,
            ],
            [
                'start' => '18:00',
                'venue' => 'Slachthuis',
                'artists' => [
                    'Martin Garrix',
                ],
                'session' => 'Club',
                'duration' => 90,
                'tickets_available' => 200,
                'price' => 60.00,
            ],
        ],
        'notes' => [
            '* All-Access pass for this day €150,00, All-Access pass for Fri, Sat,Sun: €250,00.<br>The capacity of the Club sessions is very limited. Availability for All-Access pas holders can not be garanteed due to safety regulations.<br>Tickets available represents total capacity. (90% is sold as single tickets. 10% of total capacity is left for Walk ins/All-Acces passholders.',
        ],
    ],
];
?>

<?php
$header_name = 'DANCE!';
$header_description = 'Get ready to experience the electrifying pulse of Haarlem\'s dance scene! From world-renowned DJs in spectacular Back2Back sets to intimate experimental sessions in iconic venues, <strong>DANCE!</strong> is your ultimate destination for house, techno, and trance. This is more than music – it’s a celebration of rhythm, energy, and connection.';
$header_dates = 'July 25 - 27, 2025';
$header_image = '/assets/img/events/slider/dance.png';

include_once __DIR__ . '/../components/header.php';
?>

<div class="container artist-grid">
    <?php $artistCount = 0; ?>
    <?php foreach ($artists as $artist) { ?>
        <?php if ($artistCount % 3 == 0) { ?>
            <div class="row">
            <?php } ?>

            <div class="col-md-4 artist-card">
                <img src="<?php echo $artist['image']; ?>" alt="<?php echo $artist['name']; ?>">
                <h3><?php echo $artist['name']; ?></h3>
                <p><?php echo $artist['description']; ?></p>
                <a href="<?php echo $artist['link']; ?>" class="btn btn-custom-yellow"><i class="fa-solid fa-ticket"></i>
                    Tickets</a>
            </div>

            <?php ++$artistCount; ?>
            <?php if ($artistCount % 3 == 0 || $artistCount == count($artists)) { ?>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<h2 class="text-center mt-5">Locations</h2>
<div class="container-fluid p-0">
    <?php $locationCount = 0; ?>
    <?php foreach ($locations as $location) { ?>
        <?php if ($locationCount % 2 == 0) { ?>
            <div class="row g-0">
            <?php } ?>
            <div class="col-md-6 location-card" style="background-image: url('<?php echo $location['image']; ?>');">
                <div class="location-overlay">
                    <div class="location-title"><?php echo $location['name']; ?></div>
                    <div class="location-description">
                        <?php echo $location['description']; ?>
                    </div>
                    <div class="location-address">
                        <em>Address: <?php echo $location['address']; ?></em>
                    </div>
                </div>
            </div>

            <?php ++$locationCount; ?>
            <?php if ($locationCount % 2 == 0 || $locationCount == count($locations)) { ?>
            </div>
        <?php } ?>
    <?php } ?>
</div>
<h2 class="text-center mt-5">The Schedule</h2>
<?php foreach ($schedules as $schedule) { ?>
    <div class="container table-container">
        <h2 class="text-center"><?php echo $schedule['date']; ?></h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Starting Time</th>
                    <th>Venue</th>
                    <th>Artists</th>
                    <th>Session</th>
                    <th>Duration</th>
                    <th>Tickets Available</th>
                    <th>Price</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedule['rows'] as $row) { ?>
                    <tr>
                        <td><?php echo $row['start']; ?></td>
                        <td><?php echo $row['venue']; ?></td>
                        <td><?php echo implode(', ', $row['artists']); ?></td>
                        <td><?php echo $row['session']; ?></td>
                        <td><?php echo $row['duration']; ?> min</td>
                        <td><?php echo $row['tickets_available']; ?></td>
                        <td>&euro;<?php echo $row['price']; ?></td>
                        <td><button class="btn btn-custom-yellow" onclick="openModal()"
                                data-start="<?php echo $row['start']; ?>" data-venue="<?php echo $row['venue']; ?>"
                                data-artists="<?php echo implode(', ', $row['artists']); ?>"
                                data-price="<?php echo $row['price']; ?>" data-day="<?php echo $schedule['date']; ?>"
                                data-duration="<?php echo $row['duration']; ?>"><i class="fa fa-ticket"></i> Buy
                                now</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <p class="text-center text-light small">
            <?php echo implode('<br>', $schedule['notes']); ?>
        </p>
    </div>
<?php } ?>
<!-- Ticket Modal -->
<div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="section-title">Time</div>
                <div class="section-content" id="modal-time">14:00-23:00</div>

                <div class="section-title">Performing Artists</div>
                <div class="section-content" id="modal-artists">
                    Harwell<br>
                    Martin Garrix<br>
                    Armin van Buuren
                </div>

                <div class="section-title">Total</div>
                <div class="quantity-control">
                    <button class="quantity-btn decrease-btn">−</button>
                    <span class="quantity-display" id="modal-quantity">1</span>
                    <button class="quantity-btn increase-btn">+</button>
                </div>

                <div class="price-text">Total price: €110</div>

                <button class="book-btn" onclick="bookTickets()">
                    <i class="bi bi-cart"></i> Book Tickets
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    const decreaseBtn = document.querySelector('.decrease-btn');
    const increaseBtn = document.querySelector('.increase-btn');
    const quantityDisplay = document.querySelector('.quantity-display');
    const priceText = document.querySelector('.price-text');
    let eventData;
    let basePrice = 0;

    let quantity = 1;

    decreaseBtn.addEventListener('click', function () {
        if (quantity > 1) {
            quantity--;
            updateDisplay();
        }
    });

    increaseBtn.addEventListener('click', function () {
        quantity++;
        updateDisplay();
    });

    function updateDisplay() {
        quantityDisplay.textContent = quantity;
        priceText.textContent = `Total price: €${basePrice * quantity}`;
    }
    function openModal() {
        let modalInstance = bootstrap.Modal.getInstance(document.getElementById('ticketModal'));
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(document.getElementById('ticketModal'));
        }
        eventData = event.target.dataset;
        if (eventData.start === undefined) {
            eventData = event.target.parentElement.dataset;
        }
        let dateString = `${eventData.day} ${getNextOccurrence(`${eventData.day} ${eventData.start}`)} ${eventData.start}`;
        let date = new Date(dateString + ' UTC');
        let future = new Date(new Date(dateString + ' UTC').setUTCMinutes(date.getUTCMinutes() + parseInt(eventData.duration)));
        document.getElementById('modal-time').textContent = `${date.getUTCHours().toString().padStart(2, '0')}:${date.getUTCMinutes().toString().padStart(2, '0')} - ${future.getUTCHours().toString().padStart(2, '0')}:${future.getUTCMinutes().toString().padStart(2, '0')}`;
        document.getElementById('modal-artists').innerHTML = eventData.artists.replaceAll(', ', ' <br> ');
        basePrice = parseInt(eventData.price);
        updateDisplay();
        modalInstance.show();
    }
    function bookTickets() {
        let dateString = `${eventData.day} ${getNextOccurrence(`${eventData.day} ${eventData.start}`)} ${eventData.start}`;
        let date = new Date(dateString + ' UTC');
        let json = {
            "event_id": 1,
            "date": new Date(new Date(dateString + ' UTC').setUTCHours(0, 0, 0, 0)).toISOString(),
            "image": "placeholder.png",
            "name": eventData.venue,
            "artist": [],
            "starttime": date.toISOString(),
            "endtime": new Date(date.setUTCMinutes(date.getUTCMinutes() + parseInt(eventData.duration))).toISOString(),
            "price": basePrice,
            "quantity": quantity
        };
        const artists = eventData.artists.split(', ');
        for (let i = 0; i < artists.length; i++) {
            json.artist.push({
                "name": artists[i]
            });
        }
        const items = localStorage.getItem('orderedItems');
        if (items) {
            const orderedItems = JSON.parse(items);
            orderedItems.dance.push(json);
            localStorage.setItem('orderedItems', JSON.stringify(orderedItems));
        }
    }
</script>

<style>
    .header-section {
        display: flex;
        align-items: center;
        background-color: var(--primary);
        color: white;
        padding: 50px;
    }

    .header-content {
        flex: 1;
        padding-right: 30px;
    }

    .header-image {
        flex: 1;
        background: url('/assets/img/events/slider/dance.png') no-repeat center center;
        background-size: cover;
        min-height: 400px;
    }

    .header-content h1 {
        font-weight: bold;
    }

    .header-content p {
        margin-bottom: 20px;
    }

    .header-content strong {
        font-weight: bold;
    }

    .artist-grid {
        padding: 50px 0;
    }

    .artist-card {
        text-align: center;
        margin-bottom: 30px;
    }

    .artist-card img {
        width: 100%;
        height: auto;
        border-radius: 10px;
    }

    .artist-card button {
        margin-top: 10px;
        background-color: var(--buttons);
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: bold;
    }

    .location-card {
        position: relative;
        overflow: hidden;
        background-size: cover;
        background-position: center;
        min-height: 400px;
    }

    .location-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.6);
        padding: 20px;
    }

    .location-title {
        color: white;
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .location-description {
        color: white;
        font-size: 1rem;
    }

    .location-address {
        color: white;
        margin-top: 10px;
        font-size: 0.9rem;
    }

    .table-container {
        margin: 30px auto;
        width: 90%;
        background: var(--secondary-accent);
        color: white;
        padding: 20px;
        border-radius: 10px;
    }

    th,
    td {
        text-align: center;
        vertical-align: middle;
        color: white;
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