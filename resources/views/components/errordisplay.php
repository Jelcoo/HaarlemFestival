<?php if (isset($error)) { ?>
    <div class="alert alert-danger" role="alert">
        <?php if (is_array($error)) { ?>
            <ul>
                <?php foreach ($error as $e) {
                    foreach ($e as $key => $value) {
                        "<li>$value</li>";
                    }
                } ?>
            </ul>
        <?php } else {
            echo $error;
        } ?>
    </div>
<?php } ?>