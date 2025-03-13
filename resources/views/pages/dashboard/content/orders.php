<h2>Invoices</h2>

<?php if (empty($invoices)) { ?>
    <div class="alert alert-warning">No invoices found.</div>
<?php } else { ?>
    <ul class="list-group mb-4">
        <?php foreach ($invoices as $invoice) { ?>
            <li class="list-group-item">
                <div>
                    <strong>ID:</strong> <?php echo $invoice->id; ?> | 
                    <strong>Status:</strong> <?php echo $invoice->status->name; ?> |
                    <strong>Created At:</strong> <?php echo $invoice->created_at->format('Y-m-d H:i'); ?> |
                    <strong>Completed At:</strong> <?php echo $invoice->completed_at ? $invoice->completed_at->format('Y-m-d H:i') : 'N/A'; ?>
                </div>
                <div class="mt-2">
                    <button class="btn btn-primary btn-sm tickets" data-id="<?php echo $invoice->id; ?>">See Tickets</button>
                    <button class="btn btn-success btn-sm confirm" data-id="<?php echo $invoice->id; ?>">Confirm</button>
                </div>
            </li>
        <?php } ?>
    </ul>
<?php } ?>

<div class="mt-3 text-center">
    <?php $rangeStart = ($page - 1) * 10 + 1; ?>
    <?php $rangeEnd = $rangeStart + 9; ?>

    <?php if ($page > 1) { ?>
        <a href="?page=<?php echo $page - 1; ?>" class="btn btn-outline-primary">Previous</a>
    <?php } ?>
    <span class="mx-3"> <?php echo $rangeStart; ?>-<?php echo $rangeEnd; ?> </span>
    <a href="?page=<?php echo $page + 1; ?>" class="btn btn-outline-primary">Next</a>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".tickets").forEach(button => {
            button.addEventListener("click", function () {
                const invoiceId = this.getAttribute("data-id");
                window.location.href = `/dashboard/orders/tickets?invoice_id=${invoiceId}`;
            });
        });
    });
</script>
