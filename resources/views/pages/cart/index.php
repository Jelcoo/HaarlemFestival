<?php
/**
 * @var App\Models\CartItem[] $cartItems
 */
$danceCart = array_filter($cartItems, function ($item) {
    return $item->event_model === 'App\\Models\\EventDance';
});
$danceDates = getScheduleDates($danceCart);

$yummyCart = array_filter($cartItems, function ($item) {
    return $item->event_model === 'App\\Models\\EventYummy';
});
$yummyDates = getScheduleDates($yummyCart);

$historyCart = array_filter($cartItems, function ($item) {
    return $item->event_model === 'App\\Models\\EventHistory';
});
$historyDates = getScheduleDates($historyCart);

$totalItems = array_sum(array_map(function ($item) {
    return array_sum(array_map(function ($quantity) {
        return $quantity->quantity;
    }, $item->quantities));
}, $cartItems));

function formatTime($date)
{
    return date('H:i', strtotime($date));
}

function formatMoney($amount)
{
    return number_format($amount, 2);
}

function getScheduleDates(array $schedule): array
{
    $dates = array_map(function ($event) {
        return \Carbon\Carbon::parse($event->event->start_date);
    }, $schedule);
    usort($dates, function ($a, $b) {
        return $a->timestamp - $b->timestamp;
    });

    return array_unique($dates);
}

function getScheduleByDate(array $schedule, string $date): array
{
    return array_filter($schedule, function ($event) use ($date) {
        if (!isset($event->event->start_date)) {
            return false;
        }

        $dateCarbon = \Carbon\Carbon::parse($date);
        $startCarbon = \Carbon\Carbon::parse($event->event->start_date);

        return $dateCarbon->eq($startCarbon);
    });
}

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
        <div class="col-sm-12 col-lg-4" id="dance">
            <h2>DANCE!</h2>
            <?php foreach ($danceDates as $date) { ?>
                <?php $cartForDate = getScheduleByDate($danceCart, $date); ?>
                <h3><?php echo date('l F j', strtotime($date)); ?></h3>
                <?php foreach ($cartForDate as $cartItem) { ?>
                    <?php $displayedQuantity = $cartItem->quantities[0]->quantity; ?>
                    <div class="eventCard">
                        <h4><?php echo $cartItem->event->location->name; ?></h4>
                        <div>
                            <?php if (count($cartItem->event->location->assets) > 0) { ?>
                                <img src="<?php echo $cartItem->event->location->assets[0]->getUrl(); ?>" alt="Image of venue">
                            <?php } ?>
                            <div>
                                <p>Duration: <?php echo formatTime($cartItem->event->start_time); ?>-<?php echo formatTime($cartItem->event->end_time); ?></p>
                                <p>Artists: <?php echo implode(', ', array_map(fn ($artist) => $artist->name, $cartItem->event->artists)); ?></p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <p><?php echo $displayedQuantity; ?> x &euro;<?php echo formatMoney($cartItem->singlePrice()); ?> = &euro;<?php echo formatMoney($cartItem->totalPrice()); ?></p>
                            <div class="counter">
                                <form action="/cart/decrease" method="POST">
                                    <button type="submit" class="decrease-btn" <?php echo $displayedQuantity <= $danceMin ? 'disabled' : ''; ?>>
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <input type="hidden" name="item_id" value="<?php echo $cartItem->id; ?>">
                                </form>
                                <span><?php echo $displayedQuantity; ?></span>
                                <form action="/cart/increase" method="POST">
                                    <button type="submit" class="increase-btn" <?php echo !is_null($danceMax) && $displayedQuantity >= $danceMax ? 'disabled' : ''; ?>>
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                    <input type="hidden" name="item_id" value="<?php echo $cartItem->id; ?>">
                                </form>
                            </div>
                            <form action="/cart/remove" method="POST">
                                <button type="submit" class="remove-btn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <input type="hidden" name="item_id" value="<?php echo $cartItem->id; ?>">
                            </form>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php if (count($danceCart) < 1) { ?>
                <p>No events found</p>
            <?php } ?>
        </div>
        <div class="col-sm-12 col-lg-4" id="yummy">
            <h2>Yummy!</h2>
            <?php foreach ($yummyDates as $date) { ?>
                <?php $cartForDate = getScheduleByDate($yummyCart, $date); ?>
                <h3><?php echo date('l F j', strtotime($date)); ?></h3>
                <?php foreach ($cartForDate as $cartItem) { ?>
                    <?php
                    $childrenQuantity = 0;
                    $adultQuantity = 0;
                    foreach ($cartItem->quantities as $quantity) {
                        if ($quantity->type->value === 'child') {
                            $childrenQuantity += $quantity->quantity;
                        } else {
                            $adultQuantity += $quantity->quantity;
                        }
                    }
                    ?>
                    <div class="eventCard">
                        <h4><?php echo $cartItem->event->restaurant->location->name; ?></h4>
                        <div>
                            <?php if (count($cartItem->event->restaurant->assets) > 0) { ?>
                                <img src="<?php echo $cartItem->event->restaurant->assets[0]->getUrl(); ?>" alt="Image of venue">
                            <?php } ?>
                            <div>
                                <p>Duration: <?php echo formatTime($cartItem->event->start_time); ?>-<?php echo formatTime($cartItem->event->end_time); ?></p>
                                <p>Reservation cost: €<?php echo formatMoney($cartItem->singlePrice()); ?></p>
                                <?php if ($cartItem->note) { ?>
                                    <p>Notes: <?php echo $cartItem->note; ?></p>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="d-flex flex-column align-items-stretch p-0 gap-2 justify-content-between" style="flex-grow: 0.5">
                                <div class="d-flex justify-content-between p-0">
                                    <p>Adults: <?php echo $adultQuantity; ?></p>
                                    <div class="counter">
                                        <form action="/cart/decrease" method="POST">
                                            <button type="submit" class="decrease-btn" <?php echo $adultQuantity <= $yummyAdultMin ? 'disabled' : ''; ?>>
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <input type="hidden" name="item_id" value="<?php echo $cartItem->id; ?>">
                                            <input type="hidden" name="quantity_type" value="adult">
                                        </form>
                                        <span><?php echo $adultQuantity; ?></span>
                                        <form action="/cart/increase" method="POST">
                                            <button type="submit" class="increase-btn" <?php echo !is_null($yummyAdultMax) && $adultQuantity >= $yummyAdultMax ? 'disabled' : ''; ?>>
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                            <input type="hidden" name="item_id" value="<?php echo $cartItem->id; ?>">
                                            <input type="hidden" name="quantity_type" value="adult">
                                        </form>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between p-0">
                                    <p>Children: <?php echo $childrenQuantity; ?></p>
                                    <div class="counter">
                                        <form action="/cart/decrease" method="POST">
                                            <button type="submit" class="decrease-btn" <?php echo $childrenQuantity <= $yummyChildMin ? 'disabled' : ''; ?>>
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <input type="hidden" name="item_id" value="<?php echo $cartItem->id; ?>">
                                            <input type="hidden" name="quantity_type" value="child">
                                        </form>
                                        <span><?php echo $childrenQuantity; ?></span>
                                        <form action="/cart/increase" method="POST">
                                            <button type="submit" class="increase-btn" <?php echo !is_null($yummyChildMax) && $childrenQuantity >= $yummyChildMax ? 'disabled' : ''; ?>>
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                            <input type="hidden" name="item_id" value="<?php echo $cartItem->id; ?>">
                                            <input type="hidden" name="quantity_type" value="child">
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <form action="/cart/remove" method="POST">
                                <button type="submit" class="remove-btn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <input type="hidden" name="item_id" value="<?php echo $cartItem->id; ?>">
                            </form>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php if (count($yummyCart) < 1) { ?>
                <p>No events found</p>
            <?php } ?>
        </div>
        <div class="col-sm-12 col-lg-4" id="history">
            <h2>A stroll through history</h2>
            <?php foreach ($historyDates as $date) { ?>
                <?php $cartForDate = getScheduleByDate($historyCart, $date); ?>
                <h3><?php echo date('l F j', strtotime($date)); ?></h3>
                <?php foreach ($cartForDate as $cartItem) { ?>
                    <?php $displayedQuantity = $cartItem->quantities[0]->quantity; ?>
                    <?php $tourType = $cartItem->quantities[0]->type->value; ?>
                    <div class="eventCard">
                        <h4>History tour (<?php echo $cartItem->event->language; ?>)</h4>
                        <div>
                            <div>
                                <p>Duration: <?php echo formatTime($cartItem->event->start_time); ?>-<?php echo formatTime($cartItem->event->end_time); ?></p>
                                <p>Ticket type: <?php echo $tourType; ?></p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <?php if ($tourType === 'family') { ?>
                                <p>&euro;<?php echo formatMoney($cartItem->singlePrice()); ?></p>
                            <?php } else { ?>
                                <p><?php echo $displayedQuantity; ?> x &euro;<?php echo formatMoney($cartItem->singlePrice()); ?> = &euro;<?php echo formatMoney($cartItem->totalPrice()); ?></p>
                            <?php } ?>
                            <div class="counter">
                                <form action="/cart/decrease" method="POST">
                                    <button type="submit" class="decrease-btn" <?php echo ($tourType === 'family')
                                                                                    ? (($displayedQuantity <= $historyFamilyMin) ? 'disabled' : '')
                                                                                    : (($displayedQuantity <= $historySingleMin) ? 'disabled' : ''); ?>>
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <input type="hidden" name="item_id" value="<?php echo $cartItem->id; ?>">
                                    <input type="hidden" name="quantity_type" value="<?php echo $tourType; ?>">
                                </form>
                                <span><?php echo $displayedQuantity; ?></span>
                                <form action="/cart/increase" method="POST">
                                    <button type="submit" class="increase-btn" <?php echo ($tourType === 'family')
                                                                                    ? (!is_null($historyFamilyMax) && ($displayedQuantity >= $historyFamilyMax) ? 'disabled' : '')
                                                                                    : (!is_null($historySingleMax) && ($displayedQuantity >= $historySingleMax) ? 'disabled' : ''); ?>>
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                    <input type="hidden" name="item_id" value="<?php echo $cartItem->id; ?>">
                                    <input type="hidden" name="quantity_type" value="<?php echo $tourType; ?>">
                                </form>
                            </div>
                            <form action="/cart/remove" method="POST">
                                <button type="submit" class="remove-btn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <input type="hidden" name="item_id" value="<?php echo $cartItem->id; ?>">
                            </form>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php if (count($historyCart) < 1) { ?>
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