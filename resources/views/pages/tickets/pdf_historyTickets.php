<h1>History Ticket</h1>
<p><strong>Date:</strong> <?= $entry['event']->start_date->format('d F') ?> <?= $entry['event']->start_time->format('H:i') ?></p>
<p><strong>Total Seats:</strong> <?= $entry['ticket']->total_seats ?></p>
<p><strong>Type:</strong> <?= $entry['ticket']->family_ticket ? 'Family' : 'Single' ?></p>
<p><strong>Price:</strong> €<?= number_format($entry['price'], 2) ?></p>

<img src="<?= $qrPath ?>" width="150" alt="QR Code" />
