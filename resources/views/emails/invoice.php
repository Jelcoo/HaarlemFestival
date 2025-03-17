<?php
/** @var \App\Models\User $user */
/** @var \App\Models\Invoice $invoice */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #333;
        }
        .content {
            line-height: 1.6;
            color: #555;
        }
        .invoice-details {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Invoice Receipt</h2>
        </div>
        <div class="content">
            <p>Dear <?php echo htmlspecialchars($user->fullName()); ?>,</p>
            <p>Thank you for your payment. Below are the details of your invoice:</p>
            <div class="invoice-details">
                <p><strong>Invoice Number:</strong> #<?php echo $invoice->id; ?></p>
                <p><strong>Date:</strong> <?php echo $invoice->created_at->format('d-m-Y H:i'); ?></p>
                <p><strong>Amount Paid:</strong> &euro;<?php echo number_format($total, 2); ?></p>
            </div>
            <p>You can view or download your invoice by clicking the button below:</p>
            <p><a href="#" class="button">View Invoice</a></p> <!-- TODO: Add link to view invoice -->
            <p>If you have any questions, feel free to contact us.</p>
            <p>Best regards,</p>
            <p>The Festival</p>
        </div>
    </div>
</body>
</html>

