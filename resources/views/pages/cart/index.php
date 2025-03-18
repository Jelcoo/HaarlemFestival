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

$danceMin = App\Config\Config::getKey('CART_DANCE_MIN');
$danceMax = App\Config\Config::getKey('CART_DANCE_MAX');
$yummyChildMin = App\Config\Config::getKey('CART_YUMMY_CHILD_MIN');
$yummyChildMax = App\Config\Config::getKey('CART_YUMMY_CHILD_MAX');
$yummyAdultMin = App\Config\Config::getKey('CART_YUMMY_ADULT_MIN');
$yummyAdultMax = App\Config\Config::getKey('CART_YUMMY_ADULT_MAX');
$historySingleMin = App\Config\Config::getKey('CART_HISTORY_SINGLE_MIN');
$historySingleMax = App\Config\Config::getKey('CART_HISTORY_SINGLE_MAX');
$historyFamilyMin = App\Config\Config::getKey('CART_HISTORY_FAMILY_MIN');
$historyFamilyMax = App\Config\Config::getKey('CART_HISTORY_FAMILY_MAX');
?>

<div class="container mt-4">
    <?php include __DIR__ . '/../../components/errordisplay.php'; ?>
    <div class="row justify-content-center">
        <div class="col-6">
            <h1>Cart - Overview</h1>
        </div>
        <div class="col-6 d-flex gap-3 justify-content-end px-0 align-items-center">
            <h2>Total items: <span id="total-items"><?php echo $totalItems; ?></span></h2>
            <button type="button" class="btn btn-custom-yellow" data-bs-toggle="modal"
                data-bs-target="#confirmModal">Place order <i
                    class="fa-solid fa-arrow-up-right-from-square"></i></button>
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
                    <button type="submit" class="btn btn-custom-yellow">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .eventCard {
        background-color: var(--secondary);
        color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        margin-bottom: 15px;
    }

    .eventCard h4 {
        padding: 12px 16px;
        margin: 0;
        font-size: 1.4rem;
        font-weight: 600;
    }

    .eventCard>div:nth-child(2) {
        display: flex;
        padding: 0 16px 10px;
    }

    .eventCard img {
        width: 150px;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 15px;
    }

    .eventCard .d-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
    }

    .eventCard p {
        margin-bottom: 4px;
        font-size: 0.9rem;
    }

    .counter {
        display: flex;
        align-items: center;
        background-color: white;
        border-radius: 50px;
        padding: 4px 8px;
        margin: 0 10px;
    }

    .counter button {
        width: 28px;
        height: 28px;
        border: none;
        background-color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #555;
        cursor: pointer;
        padding: 0;
    }

    .counter button:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .counter span {
        width: 30px;
        text-align: center;
        color: #333;
        font-weight: 500;
    }

    .counter>div {
        display: flex;
        align-items: center;
        margin: 4px 0;
    }

    .counter>div>span:first-child {
        color: #333;
        margin-right: 8px;
        width: auto;
    }

    .remove-btn {
        width: 36px;
        height: 36px;
        background-color: var(--error);
        color: white;
        border: none;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .modal-content {
        background-color: var(--secondary-accent);
        color: white;
        border-radius: 10px;
    }

    .modal-header {
        border-bottom-color: var(--secondary);
    }

    .modal-footer {
        border-top-color: var(--secondary);
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .section-content {
        font-size: 1.2rem;
        margin-bottom: 20px;
    }
</style>