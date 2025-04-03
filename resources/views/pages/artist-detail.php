<?php
$header_name = htmlspecialchars($artist->name ?? 'Artist Not Found');
$header_description = $artist ? htmlspecialchars($artist->preview_description) : "The artist you're looking for doesn't exist.";
$header_dates = 'July 25 – 27, 2025';
$header_image = !empty($headerAsset) ? $headerAsset[0]->getUrl() : '/assets/img/placeholder2.png';

include_once __DIR__ . '/../components/header.php';
?>

<div class="container artist-grid">
    <?php if (!$artist) { ?>
        <h2 class="text-center">Artist Not Found</h2>
        <p class="text-center">The artist you're looking for doesn't exist.</p>
    <?php } else { ?>
        <div class="row mb-5">
            <div class="col-md-7 artist-details">
                <h1 class="artist-title"><?= htmlspecialchars($artist->name) ?></h1>
                <p><?= nl2br(htmlspecialchars($artist->main_description)) ?></p>
            </div>
            <div class="col-md-5 d-flex flex-column align-items-center justify-content-center">
                <?php foreach (array_slice($extraAssets ?? [], 0) as $img) { ?>
                    <div class="artist-img-wrapper mb-3 w-100">
                        <img src="<?= htmlspecialchars($img->getUrl()) ?>" alt="Artist Image" class="img-fluid rounded stacked-artist-img">
                    </div>
                <?php } ?>
            </div>
        </div>

        <h2 class="text-center mt-5">The Festival Schedule</h2>
        <div class="container table-container">
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
                    <?php foreach ($schedule as $event) { ?>
                        <tr>
                            <td><?= htmlspecialchars($event['starting_time_formatted']) ?></td>
                            <td><?= htmlspecialchars($event['location_name']) ?></td>
                            <td><?= htmlspecialchars($event['artist_names']) ?></td>
                            <td><?= \App\Enum\DanceSessionEnum::tryFrom($event['session'])?->toString() ?? htmlspecialchars($event['session']) ?></td>
                            <td><?= htmlspecialchars($event['duration']) ?> min</td>
                            <td><?= htmlspecialchars($event['tickets_available']) ?></td>
                            <td>&euro;<?= htmlspecialchars(number_format($event['price'], 2)) ?></td>
                            <td>
                                <button class="btn btn-custom-yellow" onclick="openModal()"
                                    data-event_id="<?= $event['event_id'] ?>"
                                    data-start="<?= $event['starting_time_formatted'] ?>"
                                    data-venue="<?= htmlspecialchars($event['location_name']) ?>"
                                    data-artists="<?= htmlspecialchars($event['artist_names']) ?>"
                                    data-price="<?= $event['price'] ?>"
                                    data-day="<?= $header_dates ?>"
                                    data-duration="<?= $event['duration'] ?>">
                                    <i class="fa fa-ticket"></i> Buy now
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="text-black mt-3" style="font-size: 0.9rem;">
            <p><strong>* All-Access pass for this day €150,00, All-Access pass for Fri, Sat,Sun: €250,00.</strong><br>
            The capacity of the Club sessions is very limited. Availability for All-Access pass holders cannot be guaranteed due to safety regulations.<br>
            Tickets available represent total capacity. (90% is sold as single tickets. 10% of total capacity is left for Walk-ins/All-Access passholders.)</p>
            
            <?php
            $hasTiestoWorld = false;
            foreach ($schedule as $event) {
                if (stripos($event['session'], 'tiesto_world') !== false) {
                    $hasTiestoWorld = true;
                    break;
                }
            }
            ?>

            <?php if ($hasTiestoWorld): ?>
                <p><strong>** TiëstoWorld is a special session spanning his career's work. There will also be some special guests.</strong></p>
            <?php endif; ?>

        </div>

        <?php if (!empty($albums)) { ?>
            <h2 class="text-center mt-5">Iconic Albums</h2>
            <ul>
                <?php foreach ($albums as $albumText) { ?>
                    <li><?= htmlspecialchars($albumText) ?></li>
                <?php } ?>
            </ul>
        <?php } ?>
        <?php if (!empty($albumAssets)) { ?>
            <div class="row text-center">
                <?php foreach ($albumAssets as $album) { ?>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <img src="<?= htmlspecialchars($album->getUrl()) ?>" alt="Album Cover" class="img-fluid rounded album-img">
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<!-- Ticket Modal -->
<div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="section-title">Time</div>
                <div class="section-content" id="modal-time">14:00-23:00</div>

                <div class="section-title">Performing Artists</div>
                <div class="section-content" id="modal-artists">
                    DJ Name
                </div>

                <div class="section-title">Total</div>
                <div class="quantity-control">
                    <button class="quantity-btn decrease-btn">-</button>
                    <span class="quantity-display" id="modal-quantity-display">1</span>
                    <button class="quantity-btn increase-btn">+</button>
                </div>

                <div class="price-text">Total price: €110</div>

                <form action="/cart/add" method="POST">
                    <button type="submit" class="book-btn">
                        <i class="fa fa-ticket"></i> Book Tickets
                    </button>
                    <input type="hidden" id="modal-event-type" name="event_type" value="dance">
                    <input type="hidden" id="modal-event-id" name="event_id" value="1">
                    <input type="hidden" id="modal-quantity" name="quantity" value="1">
                </form>
            </div>
        </div>
    </div>
</div>


<button data-bs-toggle="modal" data-bs-target="#socialMediaModal" class="btn btn-custom-yellow floating-button">
    <i class="fa-solid fa-share-from-square"></i> <span>Share</span>
</button>

<script>
    const decreaseBtn = document.querySelector('.decrease-btn');
    const increaseBtn = document.querySelector('.increase-btn');
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
        document.getElementById('modal-quantity-display').textContent = quantity;
        document.getElementById('modal-quantity').value = quantity;
        priceText.textContent = `Total price: €${basePrice * quantity}`;
    }

    function openModal() {
        const modalElement = document.getElementById('ticketModal');
        let modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);

        let eventData = event.target.dataset;
        if (!eventData.start) {
            eventData = event.target.parentElement.dataset;
        }

        // Construct the date string and convert to UTC
        const eventDateTime = `${eventData.day} ${getNextOccurrence(`${eventData.day} ${eventData.start}`)} ${eventData.start}`;
        const startDate = new Date(eventDateTime + ' UTC');

        // Calculate event end time
        const durationMinutes = parseInt(eventData.duration, 10);
        const endDate = new Date(startDate.getTime() + durationMinutes * 60000);

        // Format and display event time & artist in modal
        document.getElementById('modal-time').textContent = `${formatTime(startDate)} - ${formatTime(endDate)}`;
        document.getElementById('modal-artists').innerHTML = eventData.artists.replace(/, /g, ' <br> ');

        // Set invible form elements
        document.getElementById('modal-event-id').value = eventData.event_id;
        document.getElementById('modal-quantity').value = 1;

        basePrice = parseInt(eventData.price);
        quantity = 1;
        updateDisplay();

        modalInstance.show();
    }

    function formatTime(date) {
        return date.getUTCHours().toString().padStart(2, '0') + ':' + date.getUTCMinutes().toString().padStart(2, '0');
    }
</script>

<style>
    .artist-grid {
        padding: 50px 0;
    }

    .artist-img-container {
        text-align: center;
    }

    .artist-img-container img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 10px;
    }

    .artist-details {
        padding: 20px;
    }

    .artist-title {
        font-size: 2rem;
        font-weight: bold;
    }

    .table-container {
        margin: 30px auto;
        width: 90%;
        background: var(--secondary-accent);
        color: white;
        padding: 20px;
        border-radius: 10px;
    }

    th, td {
        text-align: left;
        vertical-align: middle;
        color: white;
    }

    .btn-custom-yellow {
        background-color: #f1c40f;
        border: none;
        padding: 10px 20px;
        font-weight: bold;
        border-radius: 5px;
    }

    .btn-custom-yellow:hover {
        background-color: #e1b30f;
    }

    .header-section {
        background-color: #1F4E66;
    }

    .album-img {
        max-height: 300px;
        object-fit: contain;
    }
</style>
