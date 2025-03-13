<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-4" style="max-width: 450px; width: 100%;">
        <h2 class="text-center mb-4">Your Tickets</h2>
        <?php if (empty($invoices)): ?>
            <div class="alert alert-warning text-center">It seems like you have no Tickets.</div>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($invoices as $invoice): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><strong>Your order bought:</strong> <?= $invoice->created_at->format('d-m H:i') ?></span>
                        <button class="btn btn-primary btn-sm tickets" data-id="<?= $invoice->id ?>">View Tickets</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".tickets").forEach(button => {
            button.addEventListener("click", function () {
                const invoiceId = this.getAttribute("data-id");
                window.location.href = `/program/tickets?invoice_id=${invoiceId}`;
            });
        });
    });
</script>
<?php if (isset($danceTickets) && !empty($danceTickets)): ?>
    <div class="mt-4">
        <h4 class="text-center">Your Dance Tickets</h4>
        <?php foreach ($danceTickets as $index => $danceTicket): ?>
            <div class="mb-4">
                <h5>Ticket <?= $index + 1 ?>:</h5>
                <?php if (isset($completeDanceEvents[$index])): ?>
                    <?php $completeDanceEvent = $completeDanceEvents[$index]; ?>
                    <p><strong>Event ID:</strong> <?= $completeDanceEvent->id ?></p>
                    <p><strong>Location:</strong> <?= $completeDanceEvent->location ?></p>
                    <p><strong>Session:</strong> <?= $completeDanceEvent->session ?></p>
                    <p><strong>Price:</strong> $<?= number_format($completeDanceEvent->price, 2) ?></p>
                    <p><strong>Total Tickets:</strong> <?= $completeDanceEvent->total_tickets ?></p>
                    <p><strong>Artists:</strong> <?= implode(', ', $completeDanceEvent->artists) ?></p>
                    <p><strong>Start Time:</strong> <?= $completeDanceEvent->start_date->format('d-m-Y H:i') ?></p>
                    <p><strong>End Time:</strong> <?= $completeDanceEvent->end_date->format('d-m-Y H:i') ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
