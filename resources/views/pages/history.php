<?php
$header_name = 'A stroll through History';
$header_description = 'Discover the rich history of Haarlem on a captivating guided walking tour. From the grandeur of the Church of St. Bavo to the charm of the Amsterdamse Poort, A Stroll Through History invites you to explore the city\'s most iconic landmarks. This 2.5-hour tour, complete with a refreshing break, is your chance to step into Haarlem’s past and uncover its stories of resilience, culture, and innovation.';
$header_dates = 'July 24 - 27, 2025';
$header_image = '/assets/img/events/slider/history.png';

include_once __DIR__ . '/../components/header.php';
?>

<?php if (isset($_GET['message'])) { ?>
    <?php include __DIR__ . '/../components/toast.php'; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var successToast = new bootstrap.Toast(document.getElementById("successToast"));
            successToast.show();
        });
    </script>
<?php } ?>
<link rel="stylesheet" href="/assets/css/history.css">
<h2 class="text-center mt-5">Locations</h2>
<div class="container-fluid p-0">
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php foreach ($locations as $location) { ?>
                <a href="/history/<?php echo str_replace(' ', '_', $location->name) . '_' . $location->id; ?>"
                    class="swiper-slide" <?php if (count($location->assets) > 0) { ?>
                        style="background-image: url('<?php echo $location->assets[0]->getUrl(); ?>'); text-decoration: none; color: inherit;"
                    <?php } ?>>
                    <div class="slide-content">
                        <h2><?php echo htmlspecialchars($location->name); ?></h2>
                    </div>
                </a>
            <?php } ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <div class="swiper-pagination"></div>
    </div>
</div>

<h2 class="text-center mt-5">Map</h2>
<div class="container map-container">
    <div id="map" class="h-100"></div>
</div>

<h2 class="text-center mt-5">Schedule</h2>
<div class="container p-0 mb-5">
    <?php $scheduleCount = 0; ?>
    <?php foreach ($schedules as $schedule) { ?>
        <?php if ($scheduleCount % 4 == 0) { ?>
            <div class="row g-0 gap-2 justify-content-center">
            <?php } ?>

            <div class="tour-ticket-card card shadow-sm">
                <div class="card-header text-center">
                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($schedule['date']); ?></h5>
                </div>
                <div class="card-body">
                    <div class="tour-detail">
                        <div class="row mb-2">
                            <div class="col-5 text-muted">Start Location</div>
                            <div class="col-7 text-end"><?php echo htmlspecialchars($schedule['location']); ?></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-muted">Seats per Tour</div>
                            <div class="col-7 text-end"><?php echo htmlspecialchars($schedule['seats_per_tour']); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 text-muted">Prices</div>
                            <div class="col-7 text-end">
                                <div>Single: €<?php echo htmlspecialchars($schedule['prices']['single']); ?></div>
                                <div>Family: €<?php echo htmlspecialchars($schedule['prices']['family']); ?> *</div>
                            </div>
                        </div>

                        <div class="guides-section mb-3">
                            <h6 class="text-center mb-2">Guides</h6>
                            <?php foreach ($schedule['guides'] as $guide) { ?>
                                <div class="row mb-1">
                                    <div class="col-5 text-muted"><?php echo htmlspecialchars($guide['language']); ?></div>
                                    <div class="col-7 text-end"><?php echo htmlspecialchars(implode(', ', $guide['names'])); ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="starting-times">
                            <h6 class="text-center mb-2">Starting Time</h6>
                            <div class="time-slots">
                                <?php foreach ($schedule['start'] as $start) { ?>
                                    <div class="row mb-1">
                                        <div class="col-6"><?php echo htmlspecialchars($start['time']); ?></div>
                                        <div class="col-6 text-end">
                                            <?php
                                            $tours = array_map(function ($lang, $count) {
                                                return count($count) . "x $lang";
                                            }, array_keys($start['tours']), array_values($start['tours']));
                                            echo implode('<br>', $tours);
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-custom-yellow w-100"
                        data-price-family="<?php echo $schedule['prices']['family']; ?>"
                        data-price-single="<?php echo $schedule['prices']['single']; ?>" data-tours="<?php foreach ($schedule['start'] as $start) {
                               // Start with the time followed by a dot
                               echo $start['time'] . '.';

                               $langStrings = [];
                               // Loop through each language and its array of tour IDs
                               foreach ($start['tours'] as $lang => $ids) {
                                   // Build a string in the format "Language:id1,id2"
                                   $langStrings[] = $lang . ':' . implode(',', $ids);
                               }

                               // Join all language strings with a "?" delimiter, and end with a semicolon
                               echo implode('?', $langStrings) . ';';
                           } ?>" data-date="<?php echo $schedule['date']; ?>" onclick="openModal()"><i
                            class="fa-solid fa-ticket"></i>
                        Buy Ticket</button>
                </div>
            </div>

            <?php ++$scheduleCount; ?>
            <?php if ($scheduleCount % 4 == 0 || $scheduleCount == count($locations)) { ?>
            </div>
        <?php } ?>
    <?php } ?>
</div>
<div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="booking-container">
                    <div class="section-title" id="date">Date</div>

                    <div class="field-label">Language:</div>
                    <select class="form-select dropdown-select" id="languageSelect">
                        <option value="" selected>Select Language</option>
                        <!-- Will be populated via the data keys -->
                    </select>

                    <div class="field-label">Session:</div>
                    <select class="form-select dropdown-select" id="sessionSelect" disabled>
                        <option value="" selected>Select Session</option>
                        <!-- Will be populated via the data keys -->
                    </select>

                    <div class="field-label">Ticket Type:</div>
                    <select class="form-select dropdown-select" id="ticketSelect" disabled>
                        <option value="" selected>Select Ticket</option>
                        <option value="single" data-price="<?php echo $schedule['prices']['single']; ?>">Single
                            (€<?php echo htmlspecialchars($schedule['prices']['single']); ?>)
                        </option>
                        <option value="family" data-price="<?php echo $schedule['prices']['family']; ?>">Family
                            (€<?php echo htmlspecialchars($schedule['prices']['family']); ?>)</option>
                    </select>

                    <div class="quantity-control">
                        <button class="quantity-btn decrease-btn">-</button>
                        <span class="quantity-display">1</span>
                        <button class="quantity-btn increase-btn">+</button>
                    </div>

                    <div class="price-text">Total price: €0</div>

                    <form action="/cart/add" method="POST">
                        <button type="submit" class="book-btn" disabled>
                            <i class="bi bi-cart"></i> Book Tickets
                        </button>
                        <input type="hidden" id="modal-event-type" name="event_type" value="history">
                        <input type="hidden" id="modal-event-ids" name="event_ids" value="1">
                        <input type="hidden" id="modal-ticket-type" name="ticket_type" value="single">
                        <input type="hidden" id="modal-quantity" name="quantity" value="1">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<button data-bs-toggle="modal" data-bs-target="#socialMediaModal" class="btn btn-custom-yellow floating-button">
    <i class="fa-solid fa-share-from-square"></i> <span>Share</span>
</button>

<script src="/assets/js/utils.js"></script>
<script>
    const swiper = new Swiper('.swiper', {
        direction: 'horizontal',
        loop: true,
        slidesPerView: 3,

        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },

        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        }
    });

    const map = L.map('map').setView([52.39330619537042, 4.635887145996095], 14);

    L.tileLayer(`https://basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}@2x.png`, {
        minZoom: 12,
        maxZoom: 18,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    const locations = <?php echo json_encode($locations); ?>;

    locations.forEach(location => {
        const coords = location.coordinates.split(',');
        L.marker(coords).addTo(map)
            .bindPopup(`
                <h4>${location.name}</h4>
                <p><em>Address: ${location.address}</em></p>
            `);
    });

    setTimeout(() => map.invalidateSize(), 100);

    // DOM elements
    const dateElement = document.getElementById('date');
    const languageSelect = document.getElementById('languageSelect');
    const sessionSelect = document.getElementById('sessionSelect');
    const ticketSelect = document.getElementById('ticketSelect');
    const decreaseBtn = document.querySelector('.decrease-btn');
    const increaseBtn = document.querySelector('.increase-btn');
    const quantityDisplay = document.querySelector('.quantity-display');
    const priceText = document.querySelector('.price-text');
    const bookBtn = document.querySelector('.book-btn');

    let quantity = 1;
    let ticketPrice = 0;
    let selectedTourId = null;
    let tourData;
    let eventData;

    function openModal() {
        let modalInstance = bootstrap.Modal.getInstance(document.getElementById('ticketModal'));
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(document.getElementById('ticketModal'));
        }
        eventData = event.target.dataset;
        dateElement.textContent = eventData.date;

        tourData = parseData(event);

        // Reset the modal
        resetModal();

        initializeLanguageDropdown();
        modalInstance.show();
    }

    // Function to reset modal when it opens again
    function resetModal() {
        while (languageSelect.options.length > 1) {
            languageSelect.remove(1);
        }
        languageSelect.selectedIndex = 0;

        while (sessionSelect.options.length > 1) {
            sessionSelect.remove(1);
        }
        sessionSelect.selectedIndex = 0;
        sessionSelect.disabled = true;

        if (ticketSelect) {
            ticketSelect.selectedIndex = 0;
            ticketSelect.disabled = true;
        }

        quantity = 1;
        quantityDisplay.textContent = quantity;

        ticketPrice = 0;
        priceText.textContent = `Total price: €0`;

        bookBtn.disabled = true;

        selectedTourId = null;
    }

    // Initialize the language dropdown
    function initializeLanguageDropdown() {
        // Get unique languages from the data
        const languages = new Set();
        tourData.forEach(timeSlot => {
            Object.keys(timeSlot.tours).forEach(lang => {
                languages.add(lang);
            });
        });

        // Add languages to dropdown
        languages.forEach(lang => {
            const option = document.createElement('option');
            option.value = lang;
            option.textContent = lang;
            languageSelect.appendChild(option);
        });
    }

    // Update session dropdown based on selected language
    function updateSessionDropdown() {
        // Clear previous options except the first one
        while (sessionSelect.options.length > 1) {
            sessionSelect.remove(1);
        }

        // Disable if no language is selected
        if (!languageSelect.value) {
            sessionSelect.disabled = true;
            return;
        }

        // Enable and populate with new options
        sessionSelect.disabled = false;

        // Add time slots that have the selected language
        tourData.forEach(timeSlot => {
            if (timeSlot.tours[languageSelect.value]) {
                const option = document.createElement('option');
                option.value = timeSlot.time;
                option.textContent = timeSlot.time;
                option.dataset.tourIds = JSON.stringify(timeSlot.tours[languageSelect.value]);
                sessionSelect.appendChild(option);
            }
        });
    }

    // Update ticket selection based on session
    function updateTicketDropdown() {
        // Enable ticket selection if session is selected
        ticketSelect.disabled = !sessionSelect.value;

        if (sessionSelect.value) {
            // Get tour IDs for the selected time and language
            const selectedOption = sessionSelect.options[sessionSelect.selectedIndex];
            if (selectedOption.dataset.tourIds) {
                const tourIds = JSON.parse(selectedOption.dataset.tourIds);
                selectedTourId = tourIds;
            }
        } else {
            selectedTourId = null;
        }

        document.getElementById('modal-event-ids').value = selectedTourId;
    }

    // Update price display
    function updatePriceDisplay() {
        if (!ticketSelect.value) {
            ticketPrice = 0;
            bookBtn.disabled = true;
        } else {
            const selectedOption = ticketSelect.options[ticketSelect.selectedIndex];
            ticketPrice = selectedOption.dataset.price ? parseFloat(selectedOption.dataset.price) : 0;
            bookBtn.disabled = false;
        }
        if (ticketSelect.value == 'family') {
            if (quantity > 4) {
                quantity = 4;
                quantityDisplay.textContent = quantity;
            }
            priceText.textContent = `Total price: €${ticketPrice}`;
        } else {
            priceText.textContent = `Total price: €${ticketPrice * quantity}`;
        }

        document.getElementById('modal-quantity').value = quantity;
    }

    // Quantity control
    decreaseBtn.addEventListener('click', function () {
        if (quantity > 1) {
            quantity--;
            quantityDisplay.textContent = quantity;
            updatePriceDisplay();
        }
    });

    increaseBtn.addEventListener('click', function () {
        if (ticketSelect.value == 'family' && quantity < 4) {
            quantity++;
        } else if (ticketSelect.value == 'single') {
            quantity++
        }
        quantityDisplay.textContent = quantity;
        updatePriceDisplay();
    });

    // Event listeners for dropdowns
    languageSelect.addEventListener('change', function () {
        updateSessionDropdown();
        updateTicketDropdown();
        updatePriceDisplay();
    });

    sessionSelect.addEventListener('change', function () {
        updateTicketDropdown();
        updatePriceDisplay();
    });

    ticketSelect.addEventListener('change', function () {
        document.getElementById('modal-ticket-type').value = ticketSelect.value;
        updatePriceDisplay();
    });

    function parseData(event) {
        let eventData = event.target.dataset;
        if (eventData.tours === undefined) {
            eventData = event.target.parentElement.dataset;
        }

        let dataStr = eventData.tours;
        // Spliting the different timesschedules
        const entries = dataStr.split(";").filter(entry => entry.trim() !== "");

        const schedule = entries.map(entry => {
            // Split by . to get the time
            const [time, toursStr] = entry.split(".");

            // Split the tours string by "?" to get each language entry
            const tourEntries = toursStr.split("?");

            // Loop through each language to build the object
            const tours = tourEntries.reduce((acc, tourEntry) => {
                const [language, idsStr] = tourEntry.split(":");
                if (language && idsStr) {
                    // Convert ids to an array of numbers
                    acc[language] = idsStr.split(",").map(id => parseInt(id, 10));
                }
                return acc;
            }, {});

            return { time, tours };
        });

        return schedule;
    }
</script>