<h2>Tickets</h2>
<button class="back">back</button>
<?php if (!empty($danceTickets)) { ?>
    <h3>Dance Tickets</h3>
    <ul>
        <?php foreach ($danceTickets as $ticket) { ?>
            <li>
                <strong>Ticket ID:</strong> <?php echo $ticket->id; ?> |
                <strong>Event ID:</strong> <?php echo $ticket->dance_event_id; ?> |
                <strong>All Access:</strong> <?php echo $ticket->all_access ? 'Yes' : 'No'; ?> |
                <strong>Used:</strong> <?php echo $ticket->ticket_used ? 'Yes' : 'No'; ?>
            </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <p>No dance tickets found.</p>
<?php } ?>

<?php if (!empty($historyTickets)) { ?>
    <h3>History Tickets</h3>
    <ul>
        <?php foreach ($historyTickets as $ticket) { ?>
            <li>
                <strong>Ticket ID:</strong> <?php echo $ticket->id; ?> |
                <strong>Event ID:</strong> <?php echo $ticket->history_event_id; ?> |
                <strong>Total Seats:</strong> <?php echo $ticket->total_seats; ?> |
                <strong>Family Ticket:</strong> <?php echo $ticket->family_ticket ? 'Yes' : 'No'; ?> |
                <strong>Used:</strong> <?php echo $ticket->ticket_used ? 'Yes' : 'No'; ?>
            </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <p>No history tickets found.</p>
<?php } ?>

<?php if (!empty($yummyTickets)) { ?>
    <h3>Yummy Tickets</h3>
    <ul>
        <?php foreach ($yummyTickets as $ticket) { ?>
            <li>
                <strong>Ticket ID:</strong> <?php echo $ticket->id; ?> |
                <strong>Event ID:</strong> <?php echo $ticket->yummy_event_id; ?> |
                <strong>Kids:</strong> <?php echo $ticket->kids_count; ?> |
                <strong>Adults:</strong> <?php echo $ticket->adult_count; ?> |
                <strong>QR Code:</strong> <?php echo $ticket->qrcode; ?> |
                <strong>Used:</strong> <?php echo $ticket->ticket_used ? 'Yes' : 'No'; ?>
            </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <p>No yummy tickets found.</p>
<?php } ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".back").forEach(button => {
        button.addEventListener("click", function () {
            window.location.href = "/dashboard/orders"; 
        });
    });
});

</script>