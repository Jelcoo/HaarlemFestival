<?php
echo "QR Code: $ticketId <br>";
echo "Used: " . ($used ? 'true' : 'false') . "<br>";
echo "Event Type: $eventType <br>";
echo "Ticket Event: $ticketEvent <br>";
echo "Event ID: $eventId <br>";
?>
<?php if (!$used): ?>
    <h1>Ticket is valid</h1>
    <p>Use ticket</p>
    <button onclick="confirmUse('<?php echo htmlspecialchars($eventType, ENT_QUOTES); ?>', '<?php echo htmlspecialchars($ticketId, ENT_QUOTES); ?>', <?php echo (int)$eventId; ?>)">Use</button>
<?php else: ?>
    <h1>Ticket is not valid</h1>
    <button onclick="goBack()">Go back</button>
<?php endif; ?>

<script>
function confirmUse(eventType, ticketId, eventId) {
    fetch('/useTicket', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ eventType, ticketId, eventId })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Server Response:", data);
        if (data.success) {
            window.location.href = "/qrcode";
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Something went wrong');
    });
}

function goBack(){
    window.location.href = `/qrcode`;
}
</script>
