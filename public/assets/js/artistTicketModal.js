const decreaseBtn = document.querySelector(".decrease-btn");
const increaseBtn = document.querySelector(".increase-btn");
const priceText = document.querySelector(".price-text");
let eventData;
let basePrice = 0;

let quantity = 1;

decreaseBtn.addEventListener("click", function () {
  if (quantity > 1) {
    quantity--;
    updateDisplay();
  }
});

increaseBtn.addEventListener("click", function () {
  quantity++;
  updateDisplay();
});

function updateDisplay() {
  document.getElementById("modal-quantity-display").textContent = quantity;
  document.getElementById("modal-quantity").value = quantity;
  priceText.textContent = `Total price: €${basePrice * quantity}`;
}

function openModal() {
  const modalElement = document.getElementById("ticketModal");
  let modalInstance =
    bootstrap.Modal.getInstance(modalElement) ||
    new bootstrap.Modal(modalElement);

  let eventData = event.target.dataset;
  if (!eventData.start) {
    eventData = event.target.parentElement.dataset;
  }

  // Construct the date string and convert to UTC
  const eventDateTime = `${eventData.day} ${getNextOccurrence(
    `${eventData.day} ${eventData.start}`
  )} ${eventData.start}`;
  const startDate = new Date(eventDateTime + " UTC");

  // Calculate event end time
  const durationMinutes = parseInt(eventData.duration, 10);
  const endDate = new Date(startDate.getTime() + durationMinutes * 60000);

  // Format and display event time & artist in modal
  document.getElementById("modal-time").textContent = `${formatTime(
    startDate
  )} - ${formatTime(endDate)}`;
  document.getElementById("modal-artists").innerHTML =
    eventData.artists.replace(/, /g, " <br> ");

  // Set invible form elements
  document.getElementById("modal-event-id").value = eventData.event_id;
  document.getElementById("modal-quantity").value = 1;

  basePrice = parseInt(eventData.price);
  quantity = 1;
  updateDisplay();

  modalInstance.show();
}

function formatTime(date) {
  return (
    date.getUTCHours().toString().padStart(2, "0") +
    ":" +
    date.getUTCMinutes().toString().padStart(2, "0")
  );
}
