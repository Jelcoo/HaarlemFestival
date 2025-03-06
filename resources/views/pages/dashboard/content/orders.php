<h2>Invoices</h2>

<?php if (empty($invoices)): ?>
    <p>No invoices found.</p>
<?php else: ?>
    <ul>
        <?php foreach ($invoices as $invoice): ?>
            <li>
                <strong>ID:</strong> <?= $invoice->id ?> |
                <strong>Status:</strong> <?= $invoice->status->name ?> |
                <strong>Created At:</strong> <?= $invoice->created_at->format('Y-m-d H:i') ?> |
                <strong>Completed At:</strong> <?= $invoice->completed_at ? $invoice->completed_at->format('Y-m-d H:i') : 'N/A' ?>
                <button class="tickets" data-id="<?= $invoice->id ?>">Get Tickets</button>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<div>
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>">Previous</a>
    <?php endif; ?>
    <a href="?page=<?= $page + 1 ?>">Next</a>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".tickets").forEach(button => {
            button.addEventListener("click", function () {
                const invoiceId = this.getAttribute("data-id");
                window.location.href = `http://localhost/dashboard/orders/tickets?invoice_id=${invoiceId}`;
            });
        });
    });
</script>
