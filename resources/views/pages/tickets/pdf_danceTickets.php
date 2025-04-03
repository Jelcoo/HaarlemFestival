<h1>Dance Ticket</h1>
<p><strong>Event:</strong> <?= htmlspecialchars($entry['event']->name) ?></p>
<p><strong>Date:</strong> <?= $entry['event']->start_date->format('d F') ?> <?= $entry['event']->start_time->format('H:i') ?></p>
<p><strong>Location:</strong> <?= htmlspecialchars($entry['location']->name) ?></p>
<p><strong>All Access:</strong> <?= $entry['ticket']->all_access ? 'Yes' : 'No' ?></p>
<p><strong>Price:</strong> €<?= number_format($entry['price'], 2) ?></p>

<h3>Artists</h3>
<ul>
    <?php foreach ($entry['artists'] as $artist): ?>
        <li><?= htmlspecialchars($artist->name) ?></li>
    <?php endforeach; ?>
</ul>

<img src="<?= $qrPath ?>" width="150" alt="QR Code" />
