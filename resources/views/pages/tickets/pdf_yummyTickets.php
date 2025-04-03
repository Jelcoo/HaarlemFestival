<h1>Yummy Ticket</h1>
<p><strong>Restaurant:</strong> <?php echo htmlspecialchars($entry['restaurant']->name); ?></p>
<p><strong>Location:</strong> <?php echo htmlspecialchars($entry['restaurant']->location->name); ?></p>
<p><strong>Date:</strong> <?php echo $entry['event']->start_date->format('d F'); ?> <?php echo $entry['event']->start_time->format('H:i'); ?></p>
<p><strong>Adults:</strong> <?php echo $entry['ticket']->adult_count; ?></p>
<p><strong>Kids:</strong> <?php echo $entry['ticket']->kids_count; ?></p>
<p><strong>Reservation Cost:</strong> €<?php echo number_format($entry['price'], 2); ?></p>

<img src="<?php echo $qrPath; ?>" width="150" alt="QR Code" />
