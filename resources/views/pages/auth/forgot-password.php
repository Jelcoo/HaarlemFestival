<div class="container col-md-4 mt-auto mx-auto">
    <h1>Forgot Password</h1>
    <?php include __DIR__ . '/../../components/errordisplay.php'; ?>
    <form action="/forgot-password" method="POST" class="d-flex flex-column gap-2">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Enter email" <?php echo isset($fields['email']) ? 'value="' . $fields['email'] . '"' : ''; ?>>
        </div>
        <div class="checkbox">
            <div class="cf-turnstile" data-sitekey="<?php echo App\Config\Config::getKey('TURNSTILE_KEY'); ?>"
                data-theme="light"></div>
        </div>
        <button type="submit" class="btn btn-custom-yellow">Send Reset Link</button>
    </form>

    <div class="text-center mt-3">
        <p>
            Remember your password? <a href="/login">Login</a>
        </p>
    </div>
</div> 