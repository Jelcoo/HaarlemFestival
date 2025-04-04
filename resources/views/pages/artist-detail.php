<?php
$header_name = htmlspecialchars($artist->name ?? 'Artist Not Found');
$header_description = $artist ? htmlspecialchars($artist->preview_description) : "The artist you're looking for doesn't exist.";
$header_dates = 'July 25 – 27, 2025';
$header_image = !empty($headerAsset) ? $headerAsset[0]->getUrl() : '/assets/img/placeholder2.png';

include_once __DIR__ . '/../components/header.php';
?>
<link rel="stylesheet" href="/assets/css/dance.css">
<div class="container artist-grid">
    <?php if (!$artist) { ?>
        <h2 class="text-center">Artist Not Found</h2>
        <p class="text-center">The artist you're looking for doesn't exist.</p>
    <?php } else { ?>
        <div class="row mb-5">
            <div class="col-md-7 artist-details">
                <h1 class="artist-title"><?php echo htmlspecialchars($artist->name); ?></h1>
                <p><?php echo nl2br(htmlspecialchars($artist->main_description)); ?></p>
            </div>
            <div class="col-md-5 d-flex flex-column align-items-center justify-content-center">
                <?php foreach (array_slice($extraAssets ?? [], 0) as $img) { ?>
                    <div class="artist-img-wrapper mb-3 w-100">
                        <img src="<?php echo htmlspecialchars($img->getUrl()); ?>" alt="Artist Image"
                            class="img-fluid rounded stacked-artist-img">
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
                    <?php foreach ($schedules as $schedule) { ?>
                        <?php foreach ($schedule['rows'] as $row) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($schedule['date']) . ' - ' . htmlspecialchars($row['start']); ?>
                                </td>
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
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="text-black mt-3" style="font-size: 0.9rem;">
            <p><strong>* All-Access pass for this day €150,00, All-Access pass for Fri, Sat,Sun: €250,00.</strong><br>
                The capacity of the Club sessions is very limited. Availability for All-Access pass holders cannot be
                guaranteed due to safety regulations.<br>
                Tickets available represent total capacity. (90% is sold as single tickets. 10% of total capacity is left
                for Walk-ins/All-Access passholders.)</p>

            <?php $hasTiestoWorld = false; ?>
            <?php foreach ($schedules as $schedule) { ?>

                <?php foreach ($schedule['rows'] as $row) {
                    if (stripos($row['session'], 'Tiesto World') !== false) {
                        $hasTiestoWorld = true;
                        break;
                    }
                } ?>
            <?php } ?>


            <?php if ($hasTiestoWorld) { ?>
                <p><strong>** TiëstoWorld is a special session spanning his career's work. There will also be some special
                        guests.</strong></p>
            <?php } ?>

        </div>

        <?php if (!empty($albums)) { ?>
            <h2 class="text-center mt-5">Iconic Albums</h2>
            <ul>
                <?php foreach ($albums as $albumText) { ?>
                    <li><?php echo htmlspecialchars($albumText); ?></li>
                <?php } ?>
            </ul>
        <?php } ?>
        <?php if (!empty($albumAssets)) { ?>
            <div class="row text-center">
                <?php foreach ($albumAssets as $album) { ?>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <img src="<?php echo htmlspecialchars($album->getUrl()); ?>" alt="Album Cover"
                            class="img-fluid rounded album-img">
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
<script src="/assets/js/artistTicketModal.js" defer></script>


<button data-bs-toggle="modal" data-bs-target="#socialMediaModal" class="btn btn-custom-yellow floating-button">
    <i class="fa-solid fa-share-from-square"></i> <span>Share</span>
</button>

<style>
    .header-section {
        background-color: #1F4E66;
    }
</style>