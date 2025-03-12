<h2>Tickets</h2>
<button class="back">back</button>
<?php if (!empty($danceTickets)): ?>
    <h3>Dance Tickets</h3>
    <ul>
        <?php foreach ($danceTickets as $ticket): ?>
            <li>
                <strong>Ticket ID:</strong> <?= $ticket->id ?> |
                <strong>Event ID:</strong> <?= $ticket->dance_event_id ?> |
                <strong>All Access:</strong> <?= $ticket->all_access ? 'Yes' : 'No' ?> |
                <strong>Used:</strong> <?= $ticket->ticket_used ? 'Yes' : 'No' ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No dance tickets found.</p>
<?php endif; ?>

<?php if (!empty($historyTickets)): ?>
    <h3>History Tickets</h3>
    <ul>
        <?php foreach ($historyTickets as $ticket): ?>
            <li>
                <strong>Ticket ID:</strong> <?= $ticket->id ?> |
                <strong>Event ID:</strong> <?= $ticket->history_event_id ?> |
                <strong>Total Seats:</strong> <?= $ticket->total_seats ?> |
                <strong>Family Ticket:</strong> <?= $ticket->family_ticket ? 'Yes' : 'No' ?> |
                <strong>Used:</strong> <?= $ticket->ticket_used ? 'Yes' : 'No' ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No history tickets found.</p>
<?php endif; ?>

<?php if (!empty($yummyTickets)): ?>
    <h3>Yummy Tickets</h3>
    <ul>
        <?php foreach ($yummyTickets as $ticket): ?>
            <li>
                <strong>Ticket ID:</strong> <?= $ticket->id ?> |
                <strong>Event ID:</strong> <?= $ticket->yummy_event_id ?> |
                <strong>Kids:</strong> <?= $ticket->kids_count ?> |
                <strong>Adults:</strong> <?= $ticket->adult_count ?> |
                <strong>QR Code:</strong> <?= $ticket->qrcode ?> |
                <strong>Used:</strong> <?= $ticket->ticket_used ? 'Yes' : 'No' ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No yummy tickets found.</p>
<?php endif; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".back").forEach(button => {
        button.addEventListener("click", function () {
            window.location.href = "http://localhost/dashboard/orders"; 
        });
    });
});

</script>