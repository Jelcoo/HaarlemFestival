<?php
/**
 * @var App\Models\CartItem[] $cartItems
 */
require __DIR__ . '/helpers.php';

$totalItems = array_sum(array_map(function ($item) {
    return array_sum(array_map(function ($quantity) {
        return $quantity->quantity;
    }, $item->quantities));
}, $cartItems));
?>

<?php if (isset($_GET['message'])) { ?>
    <?php include __DIR__ . '/../../components/toast.php'; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var successToast = new bootstrap.Toast(document.getElementById("successToast"));
            successToast.show();
        });
    </script>
<?php } ?>
<link rel="stylesheet" href="/assets/css/cart.css">
<div class="container mt-4">
    <?php include __DIR__ . '/../../components/errordisplay.php'; ?>
    <div class="row d-block d-md-flex justify-content-center">
        <div class="col-12 col-md-6">
            <h1>Cart - Overview</h1>
        </div>
        <div class="col-12 col-md-6 d-flex gap-3 justify-content-md-end pb-1 pb-md-0 px-md-0 align-items-center">
            <h2>Total items: <span id="total-items"><?php echo $totalItems; ?></span></h2>
            <?php if ($totalItems > 0) { ?>
                <button type="button" class="btn btn-custom-yellow" data-bs-toggle="modal"
                    data-bs-target="#confirmModal">Place order <i
                        class="fa-solid fa-arrow-up-right-from-square"></i></button>
            <?php } ?>
        </div>
        <hr>
    </div>
    <div class="row">
        <?php include __DIR__ . '/danceList.php'; ?>
        <?php include __DIR__ . '/yummyList.php'; ?>
        <?php include __DIR__ . '/historyList.php'; ?>
    </div>
</div>
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Confirm choice</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/cart" method="POST">
                    <input type="hidden" id="hiddenOrderField" name="order">
                    <div class="mb-3 d-flex flex-column">
                        <p>Do you want to pay now or pay later?</p>
                        <div>
                            <input type="radio" name="paymentChoice" id="payNow" value="payNow" checked>
                            <label for="payNow">Pay now</label>
                        </div>
                        <div>
                            <input type="radio" name="paymentChoice" id="payLater" value="payLater">
                            <label for="payLater">Pay later</label>
                        </div>
                    </div>
                    <p><em>If you are not logged in, you will be redirected to the login page (you can also register if
                            you don't have an account)</em></p>
                    <p><em>after logging in you will be redirected back to this page</em></p>
                    <button type="button" class="btn btn-custom-yellow" data-bs-dismiss="modal">Go back</button>
                    <button type="submit" class="btn btn-custom-yellow">Continue</button>
                </form>
            </div>
        </div>
    </div>
</div>