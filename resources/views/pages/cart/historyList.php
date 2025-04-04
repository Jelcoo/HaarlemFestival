<?php

/**
 * @var App\Models\CartItem[] $cartItems
 */
$historyCart = array_filter($cartItems, function ($item) {
    return $item->event_model === 'App\\Models\\EventHistory';
});
$historyDates = getScheduleDates($historyCart);

$totalItems = array_sum(array_map(function ($item) {
    return array_sum(array_map(function ($quantity) {
        return $quantity->quantity;
    }, $item->quantities));
}, $cartItems));

$historySingleMin = App\Config\Config::getKey('CART_HISTORY_SINGLE_MIN');
$historySingleMax = App\Config\Config::getKey('CART_HISTORY_SINGLE_MAX');
$historyFamilyMin = App\Config\Config::getKey('CART_HISTORY_FAMILY_MIN');
$historyFamilyMax = App\Config\Config::getKey('CART_HISTORY_FAMILY_MAX');

$historyErrors = $stockErrors['history'] ?? [];
?>

<div class="col-sm-12 col-lg-4" id="history">
    <h2>A stroll through history</h2>
    <?php foreach ($historyDates as $date) { ?>
        <?php $cartForDate = getScheduleByDate($historyCart, $date); ?>
        <h3><?php echo date('l F j', strtotime($date)); ?></h3>
        <?php foreach ($cartForDate as $cartItem) { ?>
            <?php $displayedQuantity = $cartItem->quantities[0]->quantity; ?>
            <?php $tourType = $cartItem->quantities[0]->type->value; ?>
            <div class="eventCard">
                <h4>History tour (<?php echo htmlspecialchars($cartItem->event->language); ?>)</h4>
                <div>
                    <div>
                        <p>Duration:
                            <?php echo formatTime($cartItem->event->start_time); ?>-<?php echo formatTime($cartItem->event->end_time); ?>
                        </p>
                        <p>Ticket type: <?php echo htmlspecialchars($tourType); ?></p>
                    </div>
                </div>
                <div class="d-flex">
                    <?php if ($tourType === 'family') { ?>
                        <p>&euro;<?php echo formatMoney($cartItem->singlePrice()); ?></p>
                    <?php } else { ?>
                        <p><?php echo $displayedQuantity; ?> x &euro;<?php echo formatMoney($cartItem->singlePrice()); ?> =
                            &euro;<?php echo formatMoney($cartItem->totalPrice()); ?></p>
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
                <?php if (isset($historyErrors[$cartItem->event_id])) { ?>
                    <div class="alert alert-danger mx-3" role="alert">
                        <?php echo $historyErrors[$cartItem->event_id]; ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if (count($historyCart) < 1) { ?>
        <p>No events found</p>
    <?php } ?>
</div>