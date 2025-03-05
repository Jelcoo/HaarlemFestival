<div class="container col-md-4 mt-auto mx-auto">
    <h1>Login</h1>
    <?php include __DIR__ . '/../../components/errordisplay.php'; ?>
    <form action="/login" method="POST" class="d-flex flex-column gap-2">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Enter email" <?php echo isset($fields['email']) ? 'value="' . $fields['email'] . '"' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Password" <?php echo isset($fields['password']) ? 'value="' . $fields['password'] . '"' : ''; ?>>
        </div>
        <div class="checkbox">
            <div class="cf-turnstile" data-sitekey="<?php echo App\Config\Config::getKey('TURNSTILE_KEY'); ?>" data-theme="light"></div>
        </div>
        <button type="submit" class="btn btn-custom-yellow">Login</button>
    </form>
</div>
