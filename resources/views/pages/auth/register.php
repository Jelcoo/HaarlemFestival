<div class="container">
    <h1>Register</h1>
    <?php if (isset($error)) { ?>
        <div class="alert alert-danger" role="alert">
            <?php if (is_array($error)) { ?>
                <ul>
                    <?php foreach ($error as $e): ?>
                        <?php foreach ($e as $key => $value): ?>
                            <li><?php echo $value; ?></li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ul>
            <?php } else { ?>
                <?php echo $error; ?>
            <?php } ?>
        </div>
    <?php } ?>
    <form action="/register" method="POST">
        <div class="form-group">
            <label for="first_name">First name</label>
            <input type="text" class="form-control" id="first_name" placeholder="Enter first name">
        </div>
        <div class="form-group">
            <label for="last_name">Last name</label>
            <input type="text" class="form-control" id="last_name" placeholder="Enter last name">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Enter email">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
