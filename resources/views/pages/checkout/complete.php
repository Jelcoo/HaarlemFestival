<div class="col-md-12 text-center mt-auto mb-auto">
    <?php
    switch ($status) {
        case 'succeeded': ?>
            <div class="d-flex flex-column align-items-center gap-3">
                <h1>Thank you for your purchase!</h1>
                <p class="my-0">You can find your tickets in your inbox or your account.</p>
                <a href="/account/manage" class="btn btn-custom-yellow">Go to account</a>
                <a href="/" class="btn btn-custom-yellow">Go to homepage</a>
                <p>Need help? Contact our support team at <a href="mailto:support@TheFestival.nl">support@TheFestival.nl</a></p>
            </div>
            <?php break;
        case 'failed': ?>
            <h1>Error</h1>
            <h2>Payment failed</h2>
            <p>Sorry, your payment failed. Please try again.</p>
            <p>You can find your order in your account</p>
            <a href="/account/manage" class="btn btn-custom-yellow">Go to account</a>
            <?php break;
        default: ?>
            <h1>Error</h1>
            <h2>Payment is still pending</h2>
            <p>Please check your account on the status of your payment.</p>
            <?php break;
    }
    ?>
</div>