<?php if (isset($error)) { ?>
    <div class="alert alert-danger" role="alert">
        <?php if (is_array($error)) { ?>
            <ul>
                <?php foreach ($error as $e) { ?>
                    <?php foreach ($e as $key => $value) { ?>
                        <li><?php echo $value; ?></li>
                    <?php } ?>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <?php echo $error; ?>
        <?php } ?>
    </div>
<?php } ?>
