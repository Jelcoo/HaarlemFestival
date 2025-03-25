<?php
use Carbon\Carbon;

$startDate = Carbon::parse($event['start_date'])->format('d-m-Y');
$startTime = Carbon::parse($event['start_time'])->format('H:i');
$endDate = Carbon::parse($event['end_date'])->format('d-m-Y');
$endTime = Carbon::parse($event['end_time'])->format('H:i');
?>

<div class="col-md-4">
    <div class="card mb-4">
        <div class="card-body d-flex align-items-start">
            <div class="w-100">
                <h5 class="card-title"><?php echo htmlspecialchars($event['location_name']); ?></h5>
                <p class="card-text">
                    <strong>Start:</strong> <?php echo $startTime; ?> - <?php echo $startDate; ?><br>
                    <strong>End:</strong> <?php echo $endTime; ?> - <?php echo $endDate; ?><br>
                    <strong>Artists:</strong> <?php echo htmlspecialchars($event['artist_names']); ?><br>
                    <strong>Session:</strong> <?php echo htmlspecialchars($event['session']); ?><br>
                    <strong>Duration:</strong> <?php echo $event['duration']; ?> mins<br>
                    <strong>Tickets:</strong> <?php echo $event['tickets_available']; ?> available<br>
                    <strong>Price:</strong> €<?php echo number_format((float) $event['price'], 2); ?>
                </p>

                <!-- Actions  -->
                <div class="d-flex gap-2 justify-content-end mt-3">
                    <a href="/dashboard/events/dance/edit?id=<?php echo $event['event_id']; ?>" class="btn btn-warning btn-sm">Edit</a>

                    <form action="/dashboard/events/dance/delete" method="POST" class="d-inline">
                        <input type="hidden" name="id" value="<?php echo $event['event_id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
