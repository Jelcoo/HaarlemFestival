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
        <?php foreach ($invoices as $invoice) { ?>
            <tr>
                <td><?php echo $invoice->id; ?></td>
                <td><?php echo $invoice->user_id; ?></td>
                <td><?php echo $invoice->status->name; ?></td>
                <td><?php echo $invoice->created_at->format('Y-m-d H:i'); ?></td>
                <td>
                    <a href="/invoices/pdf?id=<?php echo $invoice->id; ?>" target="_blank">Download PDF</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
