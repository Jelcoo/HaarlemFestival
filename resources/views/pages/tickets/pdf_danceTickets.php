<h1>Dance Ticket</h1>
<p><strong>Event:</strong> <?php echo htmlspecialchars($entry['event']->name); ?></p>
<p><strong>Date:</strong> <?php echo $entry['event']->start_date->format('d F'); ?> <?php echo $entry['event']->start_time->format('H:i'); ?></p>
<p><strong>Location:</strong> <?php echo htmlspecialchars($entry['location']->name); ?></p>
<p><strong>All Access:</strong> <?php echo $entry['ticket']->all_access ? 'Yes' : 'No'; ?></p>
<p><strong>Price:</strong> €<?php echo number_format($entry['price'], 2); ?></p>

<h3>Artists</h3>
<ul>
    <?php foreach ($entry['artists'] as $artist) { ?>
        <li><?php echo htmlspecialchars($artist->name); ?></li>
    <?php } ?>
</ul>

<img src="<?php echo $qrPath; ?>" width="150" alt="QR Code" />
