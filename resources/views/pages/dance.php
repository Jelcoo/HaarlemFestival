<?php
$header_name = 'DANCE!';
$header_description = 'Get ready to experience the electrifying pulse of Haarlem\'s dance scene! From world-renowned DJs in spectacular Back2Back sets to intimate experimental sessions in iconic venues, <strong>DANCE!</strong> is your ultimate destination for house, techno, and trance. This is more than music – it’s a celebration of rhythm, energy, and connection.';
$header_dates = 'July 25 - 27, 2025';
$header_image = '/assets/img/events/slider/dance.png';

include_once __DIR__ . '/../components/header.php';
?>

<?php if (isset($_GET['message'])) { ?>
    <?php include __DIR__ . '/../components/toast.php'; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var successToast = new bootstrap.Toast(document.getElementById("successToast"));
            successToast.show();
        });
    </script>
<?php } ?>
<link rel="stylesheet" href="/assets/css/dance.css">
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
                <a href="/dance/<?php echo str_replace(' ', '_', $artist->name) . '_' . $artist->id; ?>"
                    class="btn btn-custom-yellow">
                    More information <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
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
            <div class="col-md-6 location-card" <?php if (count($location->assets) > 0) { ?>
                    style="background-image: url('<?php echo $location->assets[0]->getUrl(); ?>');" <?php } ?>>
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
        <div class="table-responsive">
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
    </div>
<?php } ?>

<button data-bs-toggle="modal" data-bs-target="#socialMediaModal" class="btn btn-custom-yellow floating-button">
    <i class="fa-solid fa-share-from-square"></i> <span>Share</span>
</button>
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