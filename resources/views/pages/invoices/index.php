<h1>Invoices</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Status</th>
            <th>Created At</th>
            <th>PDF</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($invoices as $invoice): ?>
            <tr>
                <td><?= $invoice->id ?></td>
                <td><?= $invoice->user_id ?></td>
                <td><?= $invoice->status->name ?></td>
                <td><?= $invoice->created_at->format('Y-m-d H:i') ?></td>
                <td>
                    <a href="/invoices/pdf?id=<?= $invoice->id ?>" target="_blank">Download PDF</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
