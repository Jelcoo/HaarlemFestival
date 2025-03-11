<div class="col-md-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($card['title']); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($card['description']); ?></p>

            <a href="<?php echo htmlspecialchars($card['url']); ?>" class="btn btn-primary">
                Go to <?php echo htmlspecialchars($card['title']); ?>
            </a>
        </div>
    </div>
</div>
