<div class="container col-md-4 mt-auto mx-auto">
    <h1>Reset Password</h1>
    <?php include __DIR__ . '/../../components/errordisplay.php'; ?>
    <form action="/reset-password" method="POST" class="d-flex flex-column gap-2">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" class="form-control" name="password" placeholder="Enter new password" <?php echo isset($fields['password']) ? 'value="' . $fields['password'] . '"' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="password_verify">Confirm New Password</label>
            <input type="password" class="form-control" name="password_verify" placeholder="Confirm new password" <?php echo isset($fields['password_verify']) ? 'value="' . $fields['password_verify'] . '"' : ''; ?>>
        </div>
        <button type="submit" class="btn btn-custom-yellow">Reset Password</button>
    </form>

    <div class="text-center mt-3">
        <p>
            Remember your password? <a href="/login">Login</a>
        </p>
    </div>
</div> 