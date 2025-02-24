// ------- UI Resources -------
const SuccessIcon = `<span style="font-size: 2em; color: White;"><i class="fa-solid fa-check"></i></span>`;

const ErrorIcon = `<span style="font-size: 2em; color: White;"><i class="fa-solid fa-xmark"></i></span>`;

const InfoIcon = `<span style="font-size: 2em; color: White;"><i class="fa-solid fa-info"></i></span>`;

// ------- UI helpers -------
function setPaymentDetails(intent) {
  let statusText = "Something went wrong, please try again.";
  let iconColor = "#DF1B41";
  let icon = ErrorIcon;

  if (!intent) {
    setErrorState();
    return;
  }

  switch (intent.status) {
    case "succeeded":
      statusText = "Payment succeeded";
      iconColor = "#30B130";
      icon = SuccessIcon;
      break;
    case "processing":
      statusText = "Your payment is processing.";
      iconColor = "#6D6E78";
      icon = InfoIcon;
      break;
    case "requires_payment_method":
      statusText = "Your payment was not successful, please try again.";
      break;
    default:
      break;
  }

  document.querySelector("#status-icon").style.backgroundColor = iconColor;
  document.querySelector("#status-icon").innerHTML = icon;
  document.querySelector("#status-text").textContent = statusText;
  document.querySelector("#intent-id").textContent = intent.id;
  document.querySelector("#intent-status").textContent = intent.status;
  document.querySelector(
    "#view-details"
  ).href = `https://dashboard.stripe.com/payments/${intent.id}`;
}

function setErrorState() {
  document.querySelector("#status-icon").style.backgroundColor = "#DF1B41";
  document.querySelector("#status-icon").innerHTML = ErrorIcon;
  document.querySelector("#status-text").textContent =
    "Something went wrong, please try again.";
  document.querySelector("#details-table").classList.add("hidden");
  document.querySelector("#view-details").classList.add("hidden");
}

// Stripe.js instance
const stripe = Stripe(
  "pk_test_51QoOnyHKpjmVeEwAktqvUQhe6wN7jBATgJz2N0wBBW7UHWjWJklfgY03X5dc5rnaldAEQOybPATXf9WdiL65hZI000pz0NFyea"
);

checkStatus();

// Fetches the payment intent status after payment submission
async function checkStatus() {
  const clientSecret = new URLSearchParams(window.location.search).get(
    "payment_intent_client_secret"
  );

  if (!clientSecret) {
    setErrorState();
    return;
  }

  const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

  setPaymentDetails(paymentIntent);
}
