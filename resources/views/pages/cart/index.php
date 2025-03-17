<?php
/**
 * @var App\Models\CartItem[] $cartItems
 */

$danceCart = array_filter($cartItems, function ($item) {
    return $item->event_model === 'App\\Models\\EventDance';
});

$yummyCart = array_filter($cartItems, function ($item) {
    return $item->event_model === 'App\\Models\\EventYummy';
});

$historyCart = array_filter($cartItems, function ($item) {
    return $item->event_model === 'App\\Models\\EventHistory';
});
?>

<div class="container mt-4">
    <?php include __DIR__ . '/../../components/errordisplay.php'; ?>
    <div class="row justify-content-center">
        <div class="col-6">
            <h1>Cart - Overview</h1>
        </div>
        <div class="col-6 d-flex gap-3 justify-content-end px-0 align-items-center">
            <h2>Total items: <span id="total-items"><?php echo count($cartItems); ?></span></h2>
            <button type="button" class="btn btn-custom-yellow" data-bs-toggle="modal"
                data-bs-target="#confirmModal">Place order <i
                    class="fa-solid fa-arrow-up-right-from-square"></i></button>
        </div>
        <hr>
    </div>
    <div class="row">
        <div class="col-sm-12 col-lg-4" id="dance">
            <h2>DANCE!</h2>
            <?php foreach ($danceCart as $cartItem) { ?>
                <div class="eventCard" data-event-id="<?php echo $cartItem->event_id; ?>">
                    <h4><?php echo $cartItem->event->location->name; ?></h4>
                    <div>
                        <?php if (count($cartItem->event->location->assets) > 0) { ?>
                            <img src="<?php echo $cartItem->event->location->assets[0]->getUrl(); ?>" alt="Image of venue">
                        <?php } ?>
                        <div>
                            <p>Duration: <?php echo date('H:i', timestamp: strtotime($cartItem->event->start_time)); ?>-<?php echo date('H:i', timestamp: strtotime($cartItem->event->end_time)); ?></p>
                            <p>Artists: <?php echo implode(', ', array_map(fn ($artist) => $artist->name, $cartItem->event->artists)); ?></p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <p><?php echo $cartItem->quantity; ?> x &euro;<?php echo number_format($cartItem->singlePrice(), 2); ?> = &euro;<?php echo number_format($cartItem->totalPrice(), 2); ?></p>
                        <div class="counter">
                            <button type="button" class="decrease-btn" data-type="dance" data-id="1">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <span><?php echo $cartItem->quantity; ?></span>
                            <button type="button" class="increase-btn" data-type="dance" data-id="1">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <button type="button" class="remove-btn" data-type="dance" data-id="1">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            <?php } ?>
            <?php if (count($danceCart) < 1) { ?>
                <p>No events found</p>
            <?php } ?>
        </div>
        <div class="col-sm-12 col-lg-4" id="yummy">
            <h2>Yummy!</h2>
            <?php if (count($cartItems) > 0) { ?>
                <p id="danceFound">Events found</p>
            <?php } else { ?>
                <p>No events found</p>
            <?php } ?>
        </div>
        <div class="col-sm-12 col-lg-4" id="history">
            <h2>A stroll through history</h2>
            <?php if (count($cartItems) > 0) { ?>
                <p id="danceFound">Events found</p>
            <?php } else { ?>
                <p>No events found</p>
            <?php } ?>
        </div>
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

<!-- <script src="/assets/js/cart.js"></script> -->
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