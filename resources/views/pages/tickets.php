<h2>Tickets</h2>
<button class="back btn btn-outline-secondary mb-3">Back</button>
<div class="accordion" id="accordionExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" 
              data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Dance Events
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" 
         aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <?php if (!empty($completeDanceEvents)): ?>
            <div class="container mt-4">
                <?php foreach ($completeDanceEvents as $event): ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>Dance ticket - <?= implode(', ', $event['event']->artists) ?> - <?= $event['event']->start_date->format('d-m-Y') ?> <?= $event['event']->start_time->format('H:i') ?></h4>
                        </div>
                        <div class="card-body d-flex justify-content-between">
                            <div>
                                <p><strong>Location:</strong> <?= $event['event']->location->name ?></p>
                                <p><strong>Session:</strong> <?= $event['event']->session ?></p>

                                <?php if (!empty($event['ticket']->all_access) && $event['ticket']->all_access): ?>
                                    <p><strong>Access Type:</strong> All Access</p>
                                <?php else: ?>
                                    <p><strong>Price:</strong> $<?= number_format($event['event']->price, 2) ?></p>
                                    <p><strong>Incl. VAT:</strong> $<?= number_format($event['event']->vat * $event['event']->price, 2) ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="<?= $event['ticket']->qrcode ?>" alt="QR Code" class="img-fluid" style="max-width: 150px;">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No events found.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="accordion-item">
    <h2 class="accordion-header" id="headingTwo">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" 
              data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
        History Events
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" 
         aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <?php if (!empty($completeHistoryEvents)): ?>
            <div class="container mt-4">
                <?php foreach ($completeHistoryEvents as $event): ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>Tour/Event - <?= $event['event']->guide ?> - <?= $event['event']->start_date->format('d-m-Y') ?>  <?= $event['event']->start_time->format('H:i') ?></h4>
                        </div>
                        <div class="card-body d-flex justify-content-between">
                        <div>
                            <?php if ($event['ticket']->family_ticket) { ?>
                                <p><strong>Family Ticket</strong></p>
                                <p><strong>Price:</strong> $<?= number_format($event['event']->family_price, 2) ?></p>
                                <p><strong>Price with VAT Included:</strong> 
                                    $<?= number_format($event['event']->vat * $event['event']->family_price, 2) ?> 
                                </p>
                            <?php } else { ?>
                                <p><strong>Price:</strong> $<?= number_format($event['event']->single_price, 2) ?></p>
                                <p><strong>Amount of people:</strong> <?= number_format($event['ticket']->total_seats, 0) ?></p>
                                <p><strong>Price with VAT Included:</strong> 
                                    $<?= number_format($event['event']->vat * $event['event']->single_price * $event['ticket']->total_seats, 2) ?> 
                                </p>
                            <?php } ?>
                            <p><strong>Location: De grote kerk</strong></p>
                            <p><strong>Language:</strong> <?= $event['event']->language ?></p>
                        </div>

                            <div class="d-flex align-items-center">
                            <img src="<?= $event['ticket']->qrcode ?>" alt="QR Code" class="img-fluid" style="max-width: 150px;">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No events found.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="accordion-item">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" 
              data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
        Yummy Events
      </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" 
         aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <?php if (!empty($completeRestaurantEvents)): ?>
            <div class="container mt-4">
                <?php foreach ($completeRestaurantEvents as $event): ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>Yummy Ticket - <?= $event['event']->restaurant_name ?> - 
                                <?= date('Y-m-d', strtotime($event['event']->start_date)) ?> 
                                <?= date('H:i', strtotime($event['event']->start_time)) ?>
                            </h4>
                        </div>
                        <div class="card-body d-flex justify-content-between">
                            <div>
                                <p><strong>Restaurant:</strong> <?= $event['event']->restaurant_name ?></p>
                                <a href="<?= $event['event']->restaurant_website ?>" target="_blank">
                                    <?= $event['event']->restaurant_website ?>
                                </a>
                                <p><strong>Location:</strong> <?= $event['event']->location->name ?></p>
                                <p><strong>Amount of adults:</strong> <?= $event['ticket']->adult_count ?></p>
                                <p><strong>Amount of kids:</strong> <?= $event['ticket']->kids_count ?></p>
                                <p><strong>Kids Price:</strong> €<?= number_format($event['event']->kids_price, 2) ?></p>
                                <p><strong>Adult Price:</strong> €<?= number_format($event['event']->adult_price, 2) ?></p>
                                <p><strong>Total Price incl. VAT:</strong> € <?= number_format(($event['ticket']->adult_count * $event['event']->adult_price + $event['ticket']->kids_count * $event['event']->kids_price) * $event['event']->vat) ?></p>
                                </div>
                            <div class="d-flex align-items-center">
                            <img src="<?= $event['ticket']->qrcode ?>" alt="QR Code" class="img-fluid" style="max-width: 150px;">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No restaurant event tickets found.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".back").forEach(button => {
            button.addEventListener("click", function () {
                window.location.href = "/program"; 
            });
        });
    });
</script>