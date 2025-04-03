<h1>Invoice #<?= htmlspecialchars($invoice->id) ?></h1>

<p>
    <strong>Name:</strong> <?= htmlspecialchars($user->firstname . ' ' . $user->lastname) ?><br>
    <strong>Email:</strong> <?= htmlspecialchars($user->email) ?><br>
    <strong>Address:</strong> <?= htmlspecialchars($user->address ?? 'N/A') ?><br>
    <strong>Invoice Date:</strong> <?= htmlspecialchars($invoice->created_at) ?><br>
</p>

<?php if (!empty($danceTickets)): ?>
    <h2>Dance Tickets</h2>
    <table width="100%" border="1" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <th>Id</th>
                <th>Artists</th>
                <th>Date</th>
                <th>Location</th>
                <th>All Access</th>
                <th>Price (incl. VAT)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($danceTickets as $entry): ?>
                <tr>
                    <td><?= htmlspecialchars($entry['event']->id) ?></td>
                    <td>
                        <?php
                            $artistNames = array_map(fn($a) => $a->name, $entry['artists']);
                            echo htmlspecialchars(implode(', ', $artistNames));
                        ?>
                    </td>
                    <td><?= $entry['event']->start_date->format('d F') . ' ' . $entry['event']->start_time->format('H.i') ?></td>
                    <td><?= htmlspecialchars($entry['location']->name ?? 'N/A') ?></td>
                    <td><?= $entry['ticket']->all_access ? 'Yes' : 'No' ?></td>
                    <td>€<?= number_format($entry['price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php if (!empty($yummyTickets)): ?>
    <h2>Yummy Tickets</h2>
    <table width="100%" border="1" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <th>Id</th>
                <th>Restaurant</th>
                <th>Date</th>
                <th>Kids</th>
                <th>Adults</th>
                <th>Reservation Cost (incl. VAT)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($yummyTickets as $entry): ?>
                <tr>
                    <td><?= htmlspecialchars($entry['ticket']->id) ?></td>
                    <td><?= htmlspecialchars($entry['restaurant']->location->name) ?></td>
                    <td><?= $entry['event']->start_date->format('d F') . ' ' . $entry['event']->start_time->format('H.i') ?></td>
                    <td><?= $entry['ticket']->kids_count ?></td>
                    <td><?= $entry['ticket']->adult_count ?></td>
                    <td>€<?= number_format($entry['price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php if (!empty($historyTickets)): ?>
    <h2>History Tickets</h2>
    <table width="100%" border="1" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <th>Id</th>
                <th>Date</th>
                <th>Seats</th>
                <th>Ticket Type</th>
                <th>Price (incl. VAT)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historyTickets as $entry): ?>
                <tr>
                    <td><?= htmlspecialchars($entry['event']->id) ?></td>
                    <td><?= $entry['event']->start_date->format('d F') . ' ' . $entry['event']->start_time->format('H.i') ?></td>
                    <td><?= $entry['ticket']->total_seats ?></td>
                    <td><?= $entry['ticket']->family_ticket ? 'Family' : 'Single' ?></td>
                    <td>€<?= number_format($entry['price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h2>Invoice Summary</h2>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <thead>
        <tr>
            <th>Category</th>
            <th>Subtotal (excl. VAT)</th>
            <th>VAT</th>
            <th>Total (incl. VAT)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $calculateVatSplit = function(float $totalInclVat, float $vatRate): array {
            $subtotal = $totalInclVat / (1 + $vatRate);
            $vat = $totalInclVat - $subtotal;
            return [round($subtotal, 2), round($vat, 2)];
        };

        [$danceSubtotal, $danceVat] = $calculateVatSplit($totals['dance'], 0.21); // 21% VAT
        [$yummySubtotal, $yummyVat] = $calculateVatSplit($totals['yummy'], 0.09); // 9% VAT
        [$historySubtotal, $historyVat] = $calculateVatSplit($totals['history'], 0.21); // 21% VAT
        ?>
        <tr>
            <td>Dance Tickets</td>
            <td>€<?= number_format($danceSubtotal, 2) ?></td>
            <td>€<?= number_format($danceVat, 2) ?></td>
            <td>€<?= number_format($totals['dance'], 2) ?></td>
        </tr>
        <tr>
            <td>Yummy Tickets</td>
            <td>€<?= number_format($yummySubtotal, 2) ?></td>
            <td>€<?= number_format($yummyVat, 2) ?></td>
            <td>€<?= number_format($totals['yummy'], 2) ?></td>
        </tr>
        <tr>
            <td>History Tickets</td>
            <td>€<?= number_format($historySubtotal, 2) ?></td>
            <td>€<?= number_format($historyVat, 2) ?></td>
            <td>€<?= number_format($totals['history'], 2) ?></td>
        </tr>
        <tr>
            <th>Total</th>
            <th>€<?= number_format($danceSubtotal + $yummySubtotal + $historySubtotal, 2) ?></th>
            <th>€<?= number_format($danceVat + $yummyVat + $historyVat, 2) ?></th>
            <th>€<?= number_format($totals['grand'], 2) ?></th>
        </tr>
    </tbody>
</table>

