<div class="container col-md-4 mt-auto mx-auto">
    <h1>Register</h1>
    <?php include __DIR__ . '/../../components/errordisplay.php'; ?>
    <form action="/register" method="POST" class="d-flex flex-column gap-2">
        <div class="form-group">
            <label for="firstname">First name</label>
            <input type="text" class="form-control" name="firstname" placeholder="Enter first name" <?php echo isset($fields['firstname']) ? 'value="' . $fields['firstname'] . '"' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="lastname">Last name</label>
            <input type="text" class="form-control" name="lastname" placeholder="Enter last name" <?php echo isset($fields['lastname']) ? 'value="' . $fields['lastname'] . '"' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Enter email" <?php echo isset($fields['email']) ? 'value="' . $fields['email'] . '"' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Password" <?php echo isset($fields['password']) ? 'value="' . $fields['password'] . '"' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="password_verify">Password verification</label>
            <input type="password" class="form-control" name="password_verify" placeholder="Password (verification)" <?php echo isset($fields['password_verify']) ? 'value="' . $fields['password_verify'] . '"' : ''; ?>>
        </div>
        <div class="checkbox">
            <div class="cf-turnstile" data-sitekey="<?php echo App\Config\Config::getKey('TURNSTILE_KEY'); ?>" data-theme="light"></div>
        </div>
        <button type="submit" class="btn btn-custom-yellow">Register</button>
    </form>
</div>
