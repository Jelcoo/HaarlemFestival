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
                <img src="<?php echo $artist->assets[0]->getUrl(); ?>" alt="<?php echo htmlspecialchars($artist->name); ?>">
                <h3><?php echo htmlspecialchars($artist->name); ?></h3>
                <p><?php echo $artist->preview_description; ?></p>
                <a href="/dance/<?php echo htmlspecialchars($artist->id); ?>" class="btn btn-custom-yellow"><i
                        class="fa-solid fa-arrow-up-right-from-square"></i>
                    More information</a>
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
            <div class="col-md-6 location-card"
                <?php if (count($location->assets) > 0) { ?>
                style="background-image: url('<?php echo $location->assets[0]->getUrl(); ?>');"
                <?php } ?>>
                <div class="location-overlay">
                    <div class="location-title"><?php echo htmlspecialchars($location->name); ?></div>
                    <div class="location-description">
                        <?php echo $location->preview_description; ?>
                    </div>
                    <div class="location-address">
                        <em>Address: <?php echo htmlspecialchars($location->address); ?></em>
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
        <h2 class="text-center"><?php echo htmlspecialchars($schedule['date']); ?></h2>
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
                        <td><?php echo htmlspecialchars($row['start']); ?></td>
                        <td><?php echo htmlspecialchars($row['venue']); ?></td>
                        <td><?php echo htmlspecialchars(implode(', ', $row['artists'])); ?></td>
                        <td><?php echo htmlspecialchars($row['session']); ?></td>
                        <td><?php echo htmlspecialchars($row['duration']); ?> min</td>
                        <td><?php echo htmlspecialchars($row['tickets_available']); ?></td>
                        <td>&euro;<?php echo htmlspecialchars($row['price']); ?></td>
                        <td><button class="btn btn-custom-yellow" onclick="openModal()"
                                data-event_id="<?php echo $row['event_id']; ?>" data-start="<?php echo $row['start']; ?>"
                                data-venue="<?php echo $row['venue']; ?>"
                                data-artists="<?php echo implode(', ', $row['artists']); ?>"
                                data-price="<?php echo $row['price']; ?>" data-day="<?php echo $schedule['date']; ?>"
                                data-duration="<?php echo $row['duration']; ?>">
                                <i class="fa fa-ticket"></i> Buy now</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
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
    function closeModal() {
        let modalInstance = bootstrap.Modal.getInstance(document.getElementById('ticketModal'));
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(document.getElementById('ticketModal'));
        }
        modalInstance.hide();
    }
    function bookTickets() {
        let dateString = `${eventData.day} ${getNextOccurrence(`${eventData.day} ${eventData.start}`)} ${eventData.start}`;
        let date = new Date(dateString + ' UTC');
        let json = {
            "event_id": parseInt(eventData.event_id),
            "date": new Date(new Date(dateString + ' UTC').setUTCHours(0, 0, 0, 0)).toISOString(),
            "image": "placeholder.png",
            "name": eventData.venue,
            "artist": [],
            "starttime": date.toISOString(),
            "endtime": new Date(date.setUTCMinutes(date.getUTCMinutes() + parseInt(eventData.duration))).toISOString(),
            "price": basePrice,
            "quantity": quantity,
            "all_access": 0
        };
        const artists = eventData.artists.split(', ');
        for (let i = 0; i < artists.length; i++) {
            json.artist.push({
                "name": artists[i]
            });
        }
        const items = getStoredItems();
        items.dance.push(json);
        localStorage.setItem('orderedItems', JSON.stringify(items));
        closeModal();
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