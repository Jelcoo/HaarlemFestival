<h2>Invoices</h2>

<?php if (empty($invoices)): ?>
    <div class="alert alert-warning">No invoices found.</div>
<?php else: ?>
    <ul class="list-group mb-4">
        <?php foreach ($invoices as $invoice): ?>
            <li class="list-group-item">
                <div>
                    <strong>ID:</strong> <?= $invoice->id ?> | 
                    <strong>Status:</strong> <?= $invoice->status->name ?> |
                    <strong>Created At:</strong> <?= $invoice->created_at->format('Y-m-d H:i') ?> |
                    <strong>Completed At:</strong> <?= $invoice->completed_at ? $invoice->completed_at->format('Y-m-d H:i') : 'N/A' ?>
                </div>
                <div class="mt-2">
                    <button class="btn btn-primary btn-sm tickets" data-id="<?= $invoice->id ?>">See Tickets</button>
                    <button class="btn btn-success btn-sm confirm" data-id="<?= $invoice->id ?>">Confirm</button>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<div class="mt-3 text-center">
    <?php $rangeStart = ($page - 1) * 10 + 1; ?>
    <?php $rangeEnd = $rangeStart + 9; ?>

    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" class="btn btn-outline-primary">Previous</a>
    <?php endif; ?>
    <span class="mx-3"> <?= $rangeStart ?>-<?= $rangeEnd ?> </span>
    <a href="?page=<?= $page + 1 ?>" class="btn btn-outline-primary">Next</a>
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
