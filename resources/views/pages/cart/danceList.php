<?php

/**
 * @var App\Models\CartItem[] $cartItems
 */
$danceCart = array_filter($cartItems, function ($item) {
    return $item->event_model === 'App\\Models\\EventDance';
});
$danceDates = getScheduleDates($danceCart);

$danceMin = App\Config\Config::getKey('CART_DANCE_MIN');
$danceMax = App\Config\Config::getKey('CART_DANCE_MAX');
?>

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
                        <p>Artists: <?php echo implode(', ', array_map(fn($artist) => $artist->name, $cartItem->event->artists)); ?></p>
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
