<div class="container">
    <h1>Manage account</h1>
    <?php include __DIR__ . '/../../components/errordisplay.php'; ?>
    <form action="/account/manage" method="POST">
        <div class="form-group">
            <label for="firstname">First name</label>
            <input type="text" class="form-control" name="firstname" placeholder="Enter first name" <?php echo 'value="' . $user->firstname . '"'; ?>>
        </div>
        <div class="form-group">
            <label for="lastname">Last name</label>
            <input type="text" class="form-control" name="lastname" placeholder="Enter last name" <?php echo 'value="' . $user->lastname . '"'; ?>>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Enter email" <?php echo 'value="' . $user->email . '"'; ?>>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" name="address" placeholder="Enter address example: Street 1" <?php echo 'value="' . $user->address . '"'; ?>>
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" name="city" placeholder="Enter city example: Amsterdam" <?php echo 'value="' . $user->city . '"'; ?>>
        </div>
        <div class="form-group">
            <label for="postal_code">Postal code</label>
            <input type="text" class="form-control" name="postal_code" placeholder="Enter postal code example: 1111AA"
                <?php echo 'value="' . $user->postal_code . '"'; ?>>
        </div>

        <button type="submit" class="btn btn-primary">Update account</button>
    </form>
</div>