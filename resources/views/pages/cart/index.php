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
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-6">
            <h1>Cart - Overview</h1>
        </div>
        <div class="col-6 text-end">
            <h2>Total items: <span id="total-items">0</span></h2>
        </div>
        <hr>
    </div>
    <div class="row">
        <div class="col-sm-12 col-lg-4" id="dance">
            <h2>DANCE!</h2>
            <p id="danceNotFound">No events found</p>
        </div>
        <div class="col-sm-12 col-lg-4" id="yummy">
            <h2>Yummy!</h2>
            <p id="yummyNotFound">No events found</p>
        </div>
        <div class="col-sm-12 col-lg-4" id="history">
            <h2>A stroll through history</h2>
            <p id="historyNotFound">No events found</p>
        </div>
    </div>
</div>
<script>
    let reload = false;
    document.addEventListener('DOMContentLoaded', function () {
        // All the DOM elements needed
        const sections = {
            dance: {
                container: document.getElementById('dance'),
                notFoundEl: document.getElementById('danceNotFound'),
                title: 'DANCE!'
            },
            yummy: {
                container: document.getElementById('yummy'),
                notFoundEl: document.getElementById('yummyNotFound'),
                title: 'Yummy!'
            },
            history: {
                container: document.getElementById('history'),
                notFoundEl: document.getElementById('historyNotFound'),
                title: 'A stroll through history'
            }
        };

        const totalItems = document.getElementById('total-items');

        // Get data from localStorage or use empty object if none exists
        let orderedItems = JSON.parse(localStorage.getItem('orderedItems')) || {
            "dance": [],
            "yummy": [],
            "history": []
        };

        // Calculate total number of items and render all sections
        let totalCount = 0;

        // Render each section
        for (const [sectionType, items] of Object.entries(orderedItems)) {
            const section = sections[sectionType];

            if (!section) continue;

            // Reset container content
            section.container.innerHTML = `<h2>${section.title}</h2><p id="${sectionType}NotFound">No events found</p>`;

            if (items && items.length > 0) {
                section.notFoundEl = document.getElementById(`${sectionType}NotFound`);
                section.notFoundEl.style.display = 'none';

                // Group events by date
                const eventsByDate = groupByDate(items);

                // Create HTML for each date group
                for (const [date, events] of Object.entries(eventsByDate)) {
                    const dateObj = new Date(date);
                    const formattedDate = formatDate(dateObj);

                    const dateDiv = document.createElement('div');
                    dateDiv.setAttribute('data-date', date);
                    dateDiv.innerHTML = `<h3>${formattedDate}</h3>`;

                    events.forEach(event => {
                        // Create event card based on type
                        const eventCard = createEventCard(sectionType, event);
                        dateDiv.appendChild(eventCard);

                        // Update total count
                        if (sectionType === 'yummy') {
                            totalCount += event.adult_quantity + event.children_quantity;
                        } else {
                            totalCount += event.quantity;
                        }
                    });

                    section.container.appendChild(dateDiv);
                }
            }
        }

        // Update total items counter
        totalItems.textContent = totalCount;

        if (!reload) {
            // Add event listeners for buttons
            setupEventListeners();
            reload = true;
        }

        // Function to create appropriate event card based on type
        function createEventCard(type, event) {
            const eventCard = document.createElement('div');
            eventCard.className = 'eventCard';
            eventCard.dataset.eventId = event.event_id;

            const startTime = formatTime(new Date(event.starttime));
            const endTime = formatTime(new Date(event.endtime));

            // Card content depends on the type
            switch (type) {
                case 'dance':
                    eventCard.innerHTML = createDanceCardHTML(event, startTime, endTime);
                    break;
                case 'yummy':
                    eventCard.innerHTML = createYummyCardHTML(event, startTime, endTime);
                    break;
                case 'history':
                    eventCard.innerHTML = createHistoryCardHTML(event, startTime, endTime);
                    break;
            }

            return eventCard;
        }

        function createDanceCardHTML(event, startTime, endTime) {
            return `
        <h4>${event.name ? event.name : 'Event'}</h4>
        <div>
                    <img src="/assets/img/${event.image}" alt="image of the event">
            <div>
                <p>Duration: ${startTime}-${endTime}</p>
                <p>Artists: <br> ${event.artist ? event.artist.name : 'N/A'}</p>
            </div>
        </div>
        <div class="d-flex">
            <p>${event.quantity} x €${formatPrice(event.price)} = €${formatPrice(event.quantity * event.price)}</p>
            <div class="counter">
                <button type="button" class="decrease-btn" data-type="dance" data-id="${event.event_id}">
                    <i class="fa-solid fa-minus"></i>
                </button>
                <span>${event.quantity}</span>
                <button type="button" class="increase-btn" data-type="dance" data-id="${event.event_id}">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
            <button type="button" class="remove-btn" data-type="dance" data-id="${event.event_id}">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `;
        }

        function createYummyCardHTML(event, startTime, endTime) {
            const totalAdultPrice = event.adult_price * event.adult_quantity;
            const totalChildrenPrice = event.children_price * event.children_quantity;
            const totalPrice = totalAdultPrice + totalChildrenPrice;

            return `
        <h4>${event.restaurant ? event.restaurant.name : 'Restaurant'}</h4>
        <div>
                    <img src="/assets/img/${event.image}" alt="image of the restaurant">
            <div>
                <p>Duration: ${startTime}-${endTime}</p>
                <p>Reservation cost: €${event.reservationcost ? event.reservationcost : 'N/A'}</p>
                ${event.notes ? `<p>Notes: ${event.notes}</p>` : ''}
            </div>
        </div>
        <div class="d-flex">
            <div class="d-flex flex-column align-items-stretch p-0 gap-2 justify-content-between" style="flex-grow: 0.5">
                <div class="d-flex justify-content-between p-0">
                    <p>Adults: ${event.adult_quantity}</p>
                    <div class="counter">
                        <button type="button" class="decrease-btn" data-type="yummy" data-id="${event.event_id}" data-category="adult">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <span>${event.adult_quantity}</span>
                        <button type="button" class="increase-btn" data-type="yummy" data-id="${event.event_id}" data-category="adult">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="d-flex justify-content-between p-0">
                    <p>Children: ${event.children_quantity}</p>
                    <div class="counter">
                        <button type="button" class="decrease-btn" data-type="yummy" data-id="${event.event_id}" data-category="children">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <span>${event.children_quantity}</span>
                        <button type="button" class="increase-btn" data-type="yummy" data-id="${event.event_id}" data-category="children">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" class="remove-btn align-self-end" data-type="yummy" data-id="${event.event_id}">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `;
        }

        function createHistoryCardHTML(event, startTime, endTime) {
            return `
        <h4>${event.name}</h4>
        <div>
                    <img src="/assets/img/${event.image}" alt="image of the history tour">
            <div>
                <p>Duration: ${startTime}-${endTime}</p>
            </div>
        </div>
        <div class="d-flex">
            <p>${event.quantity} x €${formatPrice(event.price)} = €${formatPrice(event.quantity * event.price)}</p>
            <div class="counter">
                <button type="button" class="decrease-btn" data-type="history" data-id="${event.event_id}">
                    <i class="fa-solid fa-minus"></i>
                </button>
                <span>${event.quantity}</span>
                <button type="button" class="increase-btn" data-type="history" data-id="${event.event_id}">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
            <button type="button" class="remove-btn" data-type="history" data-id="${event.event_id}">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `;
        }

        // Helper functions
        function groupByDate(events) {
            const grouped = {};
            events.forEach(event => {
                const date = event.date;
                if (!grouped[date]) {
                    grouped[date] = [];
                }
                grouped[date].push(event);
            });
            return grouped;
        }

        function formatDate(date) {
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            const dayName = days[date.getDay()];
            const day = date.getDate();
            let suffix = 'th';
            if (day === 1 || day === 21 || day === 31) suffix = 'st';
            else if (day === 2 || day === 22) suffix = 'nd';
            else if (day === 3 || day === 23) suffix = 'rd';

            const month = months[date.getMonth()];

            return `${dayName} ${day}${suffix} ${month}`;
        }

        function formatTime(date) {
            return date.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false });
        }

        function formatPrice(price) {
            const formattedPrice = price.toFixed(2);
            // Check if the price has no cents (ends with .00)
            return formattedPrice.endsWith('.00') ? Math.floor(price) + ',-' : formattedPrice;
        }

        function setupEventListeners() {
            // Add event listeners for all buttons using event delegation
            document.addEventListener('click', function (event) {
                const target = event.target.closest('button');
                if (!target) return;

                if (target.classList.contains('increase-btn')) {
                    const type = target.dataset.type;
                    const id = parseInt(target.dataset.id);
                    const category = target.dataset.category;
                    updateQuantity(type, id, 1, category);
                }
                else if (target.classList.contains('decrease-btn')) {
                    const type = target.dataset.type;
                    const id = parseInt(target.dataset.id);
                    const category = target.dataset.category;
                    updateQuantity(type, id, -1, category);
                }
                else if (target.classList.contains('remove-btn')) {
                    const type = target.dataset.type;
                    const id = parseInt(target.dataset.id);
                    removeItem(type, id);
                }
            });
        }

        function updateQuantity(type, id, change, category = null) {
            // Find the item in the orderedItems
            const items = orderedItems[type];
            if (!items) return;

            const itemIndex = items.findIndex(item => item.event_id === id);
            if (itemIndex === -1) return;

            const item = items[itemIndex];

            // Update quantity based on type
            if (type === 'yummy' && category) {
                const quantityField = category === 'adult' ? 'adult_quantity' : 'children_quantity';
                const newQuantity = item[quantityField] + change;
                if (newQuantity <= 0 && quantityField === 'children_quantity') {
                    item[quantityField] = 0;
                } else {
                    item[quantityField] = Math.max(1, newQuantity);
                }
            } else {
                const newQuantity = Math.max(1, item.quantity + change);
                item.quantity = newQuantity;
            }

            // Save to localStorage
            localStorage.setItem('orderedItems', JSON.stringify(orderedItems));
            console.log("quantity change");
            // Refresh the display
            refreshDisplay();
        }

        function removeItem(type, id) {
            // Find and remove the item
            const items = orderedItems[type];
            if (!items) return;

            const itemIndex = items.findIndex(item => item.event_id === id);

            if (itemIndex !== -1) {
                items.splice(itemIndex, 1);

                // Save to localStorage
                localStorage.setItem('orderedItems', JSON.stringify(orderedItems));

                // Refresh the display
                refreshDisplay();
            }
        }

        function refreshDisplay() {
            // Re-initialize by triggering the DOMContentLoaded event
            document.dispatchEvent(new Event('DOMContentLoaded'));
        }
    });
</script>