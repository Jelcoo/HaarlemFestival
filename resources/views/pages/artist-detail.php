<?php
$header_name = htmlspecialchars($artist->name ?? 'Artist Not Found');
$header_description = $artist ? htmlspecialchars($artist->preview_description) : "The artist you're looking for doesn't exist.";
$header_dates = 'July 25 - 27, 2025';
$header_image = $artist->image ?? '/assets/img/artists/placeholder-artist.png';

include_once __DIR__ . '/../components/header.php';
?>

<div class="container artist-grid">
    <?php if (!$artist) { ?>
        <h2 class="text-center">Artist Not Found</h2>
        <p class="text-center">The artist you're looking for doesn't exist.</p>
    <?php } else { ?>
        <div class="row">
            <div class="col-md-6 artist-img-container">
                <img src="<?= htmlspecialchars($artist->image ?? '/assets/img/artists/placeholder-artist.png') ?>" alt="Artist Image" class="img-fluid rounded">
            </div>
            <div class="col-md-6 artist-details">
                <h1 class="artist-title"><?= htmlspecialchars($artist->name) ?></h1>
                <p><?= htmlspecialchars($artist->main_description) ?></p>
            </div>
        </div>
        
        <h2 class="text-center mt-5">The Festival Schedule</h2>
        <div class="container table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Starting Time</th>
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
                            <td><?= htmlspecialchars($event['start_date']) ?></td>
                            <td><?= htmlspecialchars($event['start_time']) ?></td>
                            <td><?= htmlspecialchars($event['session']) ?></td>
                            <td><?= htmlspecialchars($event['duration']) ?> min</td>
                            <td><?= htmlspecialchars($event['tickets_available']) ?></td>
                            <td>&euro;<?= htmlspecialchars($event['price']) ?></td>
                            <td><button class="btn btn-custom-yellow">Buy now</button></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
        <h2 class="text-center mt-5">Iconic Albums</h2>
        <ul>
            <?php foreach ($albums as $album) { ?>
                <li><?= htmlspecialchars($album) ?></li>
            <?php } ?>
        </ul>
    <?php } ?>
</div>

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
        text-align: center;
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
</style>
