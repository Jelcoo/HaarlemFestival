<?php

use App\Models\EventDance;
use App\Models\EventYummy;
use App\Models\EventHistory;

/**
 * @var App\Models\CartItem[] $cartItems
 * @var App\Models\CartItem $cartItem
 */
require __DIR__ . '/helpers.php';

$eventDates = getScheduleDates($cartItems);
?>
<div>
    <h1>Checkout</h1>
    <?php foreach ($eventDates as $date) { ?>
        <?php $cartForDate = getScheduleByDate($cartItems, $date); ?>
        <h3><?php echo date('l F j', strtotime($date)); ?></h3>
        <?php foreach ($cartForDate as $cartItem) { ?>
            <?php switch ($cartItem->event_model) {
                case EventDance::class: ?>
                    <?php $displayedQuantity = $cartItem->quantities[0]->quantity; ?>
                    <div class="eventCard">
                        <h4><?php echo $cartItem->event->location->name; ?></h4>
                        <div>
                            <div>
                                <p>Duration:
                                    <?php echo formatTime($cartItem->event->start_time); ?>-<?php echo formatTime($cartItem->event->end_time); ?>
                                </p>
                                <p>Artists:
                                    <?php echo implode(', ', array_map(fn ($artist) => $artist->name, $cartItem->event->artists)); ?>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <p><?php echo $displayedQuantity; ?> x &euro;<?php echo formatMoney($cartItem->singlePrice()); ?> =
                                &euro;<?php echo formatMoney($cartItem->totalPrice()); ?></p>
                        </div>
                    </div>
                    <?php break;
                case EventHistory::class: ?>
                    <?php $displayedQuantity = $cartItem->quantities[0]->quantity; ?>
                    <?php $tourType = $cartItem->quantities[0]->type->value; ?>
                    <div class="eventCard">
                        <h4>History tour (<?php echo $cartItem->event->language; ?>)</h4>
                        <div>
                            <div>
                                <p>Duration:
                                    <?php echo formatTime($cartItem->event->start_time); ?>-<?php echo formatTime($cartItem->event->end_time); ?>
                                </p>
                                <p>Ticket type: <?php echo $tourType; ?></p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <?php if ($tourType === 'family') { ?>
                                <p>&euro;<?php echo formatMoney($cartItem->singlePrice()); ?></p>
                            <?php } else { ?>
                                <p><?php echo $displayedQuantity; ?> x &euro;<?php echo formatMoney($cartItem->singlePrice()); ?> =
                                    &euro;<?php echo formatMoney($cartItem->totalPrice()); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                    <?php break;
                case EventYummy::class: ?>
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
                                <p>Duration:
                                    <?php echo formatTime($cartItem->event->start_time); ?>-<?php echo formatTime($cartItem->event->end_time); ?>
                                </p>
                                <p>Reservation cost: €<?php echo formatMoney($cartItem->singlePrice()); ?></p>
                                <?php if ($cartItem->note) { ?>
                                    <p>Notes: <?php echo $cartItem->note; ?></p>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="d-flex flex-column align-items-stretch p-0 gap-2 justify-content-between"
                                style="flex-grow: 0.5">
                                <div class="d-flex justify-content-between p-0">
                                    <p>Adults: <?php echo $adultQuantity; ?></p>
                                </div>
                                <div class="d-flex justify-content-between p-0">
                                    <p>Children: <?php echo $childrenQuantity; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>
</div>