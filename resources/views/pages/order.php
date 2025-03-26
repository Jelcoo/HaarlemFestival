<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-4" style="max-width: 450px; width: 100%;">
        <h2 class="text-center mb-4">Your Tickets</h2>
        <?php if (empty($invoices)) { ?>
            <div class="alert alert-warning text-center">It seems like you have no Tickets.</div>
        <?php } else { ?>
            <ul class="list-group">
                <?php foreach ($invoices as $invoice) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><strong>Your order bought:</strong> <?php echo $invoice->created_at->format('d-m H:i'); ?></span>
                        <button class="btn btn-primary btn-sm tickets" data-id="<?php echo $invoice->id; ?>">View Tickets</button>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".tickets").forEach(button => {
            button.addEventListener("click", function () {
                const invoiceId = this.getAttribute("data-id");
                window.location.href = `/order/tickets?invoice_id=${invoiceId}`;
            });
        });
    });
</script>
<?php if (isset($danceTickets) && !empty($danceTickets)) { ?>
    <div class="mt-4">
        <h4 class="text-center">Your Dance Tickets</h4>
        <?php foreach ($danceTickets as $index => $danceTicket) { ?>
            <div class="mb-4">
                <h5>Ticket <?php echo $index + 1; ?>:</h5>
                <?php if (isset($completeDanceEvents[$index])) { ?>
                    <?php $completeDanceEvent = $completeDanceEvents[$index]; ?>
                    <p><strong>Event ID:</strong> <?php echo $completeDanceEvent->id; ?></p>
                    <p><strong>Location:</strong> <?php echo $completeDanceEvent->location; ?></p>
                    <p><strong>Session:</strong> <?php echo $completeDanceEvent->session; ?></p>
                    <p><strong>Price:</strong> $<?php echo number_format($completeDanceEvent->price, 2); ?></p>
                    <p><strong>Total Tickets:</strong> <?php echo $completeDanceEvent->total_tickets; ?></p>
                    <p><strong>Artists:</strong> <?php echo implode(', ', $completeDanceEvent->artists); ?></p>
                    <p><strong>Start Time:</strong> <?php echo $completeDanceEvent->start_date->format('d-m-Y H:i'); ?></p>
                    <p><strong>End Time:</strong> <?php echo $completeDanceEvent->end_date->format('d-m-Y H:i'); ?></p>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
<?php } ?>
