<h2>Create New User</h2>

<!-- Status message -->
<?php if (!empty($status['message'])): ?>
<div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
    <?php echo htmlspecialchars($status['message']); ?>
</div>
<?php endif; ?>

<form action="/dashboard/users" method="POST">
    <input type="hidden" name="action" value="createNewUser">

    <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" class="form-control" required
            value="<?php echo htmlspecialchars($formData['firstname'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" class="form-control" required
            value="<?php echo htmlspecialchars($formData['lastname'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control" required
            value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="passowrd" id="password" name="password" class="form-control" required
            value="<?php echo htmlspecialchars($formData['password'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label for="role">Role</label>
        <select id="role" name="role" class="form-control" required>
            <?php foreach ($roles as $role) { ?>
            <option value="<?php echo $role; ?>"
                <?php echo (isset($formData['role']) && $formData['role'] == $role) ? 'selected' : ''; ?>>
                <?php echo ucfirst($role); ?>
            </option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" id="address" name="address" class="form-control"
            value="<?php echo htmlspecialchars($formData['address'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label for="city">City</label>
        <input type="text" id="city" name="city" class="form-control"
            value="<?php echo htmlspecialchars($formData['city'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label for="postal_code">Postal Code</label>
        <input type="text" id="postal_code" name="postal_code" class="form-control"
            value="<?php echo htmlspecialchars($formData['postal_code'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label for="stripe_customer_id">Stripe ID</label>
        <input type="text" id="stripe_customer_id" name="stripe_customer_id" class="form-control"
            value="<?php echo htmlspecialchars($formData['stripe_customer_id'] ?? ''); ?>">
    </div>

    <button type="submit" class="btn btn-primary">Create User</button>
</form>