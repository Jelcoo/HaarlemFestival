<?php

use App\Models\EventDance;
use App\Models\EventYummy;
use App\Models\EventHistory;

/**
 * @var App\Models\CartItem[] $cartItems
 * @var App\Models\CartItem $cartItem
 * @var App\Models\User $user
 */
require __DIR__ . '/helpers.php';

$eventDates = getScheduleDates($cartItems);
$totalExclVat = array_reduce($cartItems, function ($carry, $item) {
    return $carry + $item->totalPriceExclVAT();
}, 0);
$total = array_reduce($cartItems, function ($carry, $item) {
    return $carry + $item->totalPrice();
}, 0);
?>
<div class="container py-4">
    <h1>Checkout</h1>
    <div class="row">
        <div class="col-12 col-md-6">
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
                                            <h4 class="card-title"><?php echo htmlspecialchars($cartItem->event->location->name); ?>
                                            </h4>
                                            <div class="card-text">
                                                <p class="mb-1">
                                                    <i class="bi bi-clock"></i>
                                                    <?php echo formatTime($cartItem->event->start_time); ?>-<?php echo formatTime($cartItem->event->end_time); ?>
                                                </p>
                                                <p class="mb-3">
                                                    <i class="bi bi-music-note-beamed"></i>
                                                    <?php echo htmlspecialchars(implode(', ', array_map(fn($artist) => $artist->name, $cartItem->event->artists))); ?>
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
                                            <h4 class="card-title">History tour
                                                (<?php echo htmlspecialchars($cartItem->event->language); ?>)</h4>
                                            <div class="card-text">
                                                <p class="mb-1">
                                                    <i class="bi bi-clock"></i>
                                                    <?php echo formatTime($cartItem->event->start_time); ?>-<?php echo formatTime($cartItem->event->end_time); ?>
                                                </p>
                                                <p class="mb-3">
                                                    <i class="bi bi-ticket-perforated"></i>
                                                    Ticket type: <span
                                                        class="badge bg-secondary"><?php echo htmlspecialchars($tourType); ?></span>
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
                                            <h4 class="card-title">
                                                <?php echo htmlspecialchars($cartItem->event->restaurant->location->name); ?>
                                            </h4>
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
                                                        <small class="text-muted"><?php echo htmlspecialchars($cartItem->note); ?></small>
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
        <div class="col-12 col-md-6">
            <p class="fs-5 my-0">Subtotal &euro;<?php echo formatMoney($totalExclVat); ?></p>
            <p class="fs-5 my-0">VAT &euro;<?php echo formatMoney($total - $totalExclVat); ?></p>
            <p class="fs-5 my-0">total &euro;<?php echo formatMoney($total); ?></p>
            <hr>
            <form action="/checkout/pay" method="POST" class="d-flex gap-2 flex-column mt-4">
                <?php include __DIR__ . '/../../components/errordisplay.php'; ?>
                <?php if (isset($_GET['id']) || isset($fields['id'])) { ?>
                    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?? $fields['id']; ?>">
                <?php } ?>
                <p class="fs-5 my-0">First name: <?php echo htmlspecialchars($user->firstname); ?></p>
                <p class="fs-5 my-0">Last name: <?php echo htmlspecialchars($user->lastname); ?></p>
                <p class="fs-5 my-0">Email: <?php echo htmlspecialchars($user->email); ?></p>
                <div class="form-group">
                    <label for="phone_number">Phone number</label>
                    <input type="text" class="form-control" name="phone_number" placeholder="Enter phone number" <?php echo isset($fields['phone_number']) ? 'value="' . $fields['phone_number'] . '"' : 'value="' . $user->phone_number . '"'; ?>>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address" placeholder="Enter address example: Street 1"
                        <?php echo isset($fields['address']) ? 'value="' . $fields['address'] . '"' : 'value="' . $user->address . '"'; ?>>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" class="form-control" name="city" placeholder="Enter city example: Amsterdam"
                        <?php echo isset($fields['city']) ? 'value="' . $fields['city'] . '"' : 'value="' . $user->city . '"'; ?>>
                </div>
                <div class="form-group">
                    <label for="postal_code">Postal code</label>
                    <input type="text" class="form-control" name="postal_code"
                        placeholder="Enter postal code example: 1111AA" <?php echo isset($fields['postal_code']) ? 'value="' . $fields['postal_code'] . '"' : 'value="' . $user->postal_code . '"'; ?>>
                </div>

                <button type="submit" class="btn btn-custom-yellow w-100">Pay</button>
            </form>
        </div>
    </div>
</div>