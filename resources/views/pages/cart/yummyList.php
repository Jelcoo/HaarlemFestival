<?php

/**
 * @var App\Models\CartItem[] $cartItems
 */
$yummyCart = array_filter($cartItems, function ($item) {
    return $item->event_model === 'App\\Models\\EventYummy';
});
$yummyDates = getScheduleDates($yummyCart);

$yummyChildMin = App\Config\Config::getKey('CART_YUMMY_CHILD_MIN');
$yummyChildMax = App\Config\Config::getKey('CART_YUMMY_CHILD_MAX');
$yummyAdultMin = App\Config\Config::getKey('CART_YUMMY_ADULT_MIN');
$yummyAdultMax = App\Config\Config::getKey('CART_YUMMY_ADULT_MAX');
?>

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