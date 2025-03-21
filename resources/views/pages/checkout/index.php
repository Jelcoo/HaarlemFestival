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
<div class="container py-4">
    <h1 class="mb-4">Checkout</h1>
    <div class="col-6">
        <?php foreach ($eventDates as $date) { ?>
            <?php $cartForDate = getScheduleByDate($cartItems, $date); ?>
            <h3 class="mt-4 mb-3 border-bottom pb-2"><?php echo date('l F j', strtotime($date)); ?></h3>
            <div class="row row-cols-1 g-4 mb-4">
                <?php foreach ($cartForDate as $cartItem) { ?>
                    <div class="col">
                        <?php switch ($cartItem->event_model) {
                            case EventDance::class: ?>
                                <?php $displayedQuantity = $cartItem->quantities[0]->quantity; ?>
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo $cartItem->event->location->name; ?></h4>
                                        <div class="card-text">
                                            <p class="mb-1">
                                                <i class="bi bi-clock"></i>
                                                <?php echo formatTime($cartItem->event->start_time); ?>-<?php echo formatTime($cartItem->event->end_time); ?>
                                            </p>
                                            <p class="mb-3">
                                                <i class="bi bi-music-note-beamed"></i>
                                                <?php echo implode(', ', array_map(fn ($artist) => $artist->name, $cartItem->event->artists)); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Quantity: <?php echo $displayedQuantity; ?></span>
                                            <div class="text-end text-muted small">
                                                <span><?php echo $displayedQuantity; ?> x
                                                    &euro;<?php echo formatMoney($cartItem->singlePrice()); ?></span>
                                            </div>
                                            <span class="fw-bold">&euro;<?php echo formatMoney($cartItem->totalPrice()); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php break;
                            case EventHistory::class: ?>
                                <?php $displayedQuantity = $cartItem->quantities[0]->quantity; ?>
                                <?php $tourType = $cartItem->quantities[0]->type->value; ?>
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h4 class="card-title">History tour (<?php echo $cartItem->event->language; ?>)</h4>
                                        <div class="card-text">
                                            <p class="mb-1">
                                                <i class="bi bi-clock"></i>
                                                <?php echo formatTime($cartItem->event->start_time); ?>-<?php echo formatTime($cartItem->event->end_time); ?>
                                            </p>
                                            <p class="mb-3">
                                                <i class="bi bi-ticket-perforated"></i>
                                                Ticket type: <span class="badge bg-secondary"><?php echo $tourType; ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <?php if ($tourType === 'family') { ?>
                                                <span class="text-muted">Family package</span>
                                                <span class="fw-bold">&euro;<?php echo formatMoney($cartItem->singlePrice()); ?></span>
                                            <?php } else { ?>
                                                <span class="text-muted">Quantity: <?php echo $displayedQuantity; ?></span>
                                                <div class="text-end text-muted small">
                                                    <span><?php echo $displayedQuantity; ?> x
                                                        &euro;<?php echo formatMoney($cartItem->singlePrice()); ?></span>
                                                </div>
                                                <span class="fw-bold">&euro;<?php echo formatMoney($cartItem->totalPrice()); ?></span>
                                            <?php } ?>
                                        </div>
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
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo $cartItem->event->restaurant->location->name; ?></h4>
                                        <div class="card-text">
                                            <p class="mb-1">
                                                <i class="bi bi-clock"></i>
                                                <?php echo formatTime($cartItem->event->start_time); ?>-<?php echo formatTime($cartItem->event->end_time); ?>
                                            </p>
                                            <p class="mb-1">
                                                <i class="bi bi-cash-coin"></i>
                                                Reservation cost: &euro;<?php echo formatMoney($cartItem->singlePrice()); ?>
                                            </p>
                                            <?php if ($cartItem->note) { ?>
                                                <p class="mb-3">
                                                    <i class="bi bi-chat-left-text"></i>
                                                    <small class="text-muted"><?php echo $cartItem->note; ?></small>
                                                </p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Adults:</span>
                                                    <span><?php echo $adultQuantity; ?></span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Children:</span>
                                                    <span><?php echo $childrenQuantity; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>