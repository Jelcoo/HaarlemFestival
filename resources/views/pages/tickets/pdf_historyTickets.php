<h1>History Ticket</h1>
<p><strong>Date:</strong> <?php echo $entry['event']->start_date->format('d F'); ?> <?php echo $entry['event']->start_time->format('H:i'); ?></p>
<p><strong>Total Seats:</strong> <?php echo $entry['ticket']->total_seats; ?></p>
<p><strong>Type:</strong> <?php echo $entry['ticket']->family_ticket ? 'Family' : 'Single'; ?></p>
<p><strong>Price:</strong> €<?php echo number_format($entry['price'], 2); ?></p>

<img src="<?php echo $qrPath; ?>" width="150" alt="QR Code" />
