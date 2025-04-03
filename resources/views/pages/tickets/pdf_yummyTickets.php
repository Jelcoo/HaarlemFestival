<h1>Yummy Ticket</h1>
<p><strong>Restaurant:</strong> <?= htmlspecialchars($entry['restaurant']->name) ?></p>
<p><strong>Location:</strong> <?= htmlspecialchars($entry['restaurant']->location->name) ?></p>
<p><strong>Date:</strong> <?= $entry['event']->start_date->format('d F') ?> <?= $entry['event']->start_time->format('H:i') ?></p>
<p><strong>Adults:</strong> <?= $entry['ticket']->adult_count ?></p>
<p><strong>Kids:</strong> <?= $entry['ticket']->kids_count ?></p>
<p><strong>Reservation Cost:</strong> €<?= number_format($entry['price'], 2) ?></p>

<img src="<?= $qrPath ?>" width="150" alt="QR Code" />
