<h2>Create New User</h2>

<!-- Status message -->
<?php if (!empty($status['message'])): ?>
    <div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($status['message']); ?>
    </div>
<?php endif; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <form action="/dashboard/users" method="POST">
                <input type="hidden" name="action" value="createNewUser">

                <!-- First and Last Name -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="form-control" required
                                value="<?php echo htmlspecialchars($formData['firstname'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="form-control" required
                                value="<?php echo htmlspecialchars($formData['lastname'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group mt-3">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required
                        value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
                </div>

                <!-- Password -->
                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <!-- Role -->
                <div class="form-group mt-3">
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

                <!-- Address, City and Postal Code -->
                <div class="row mt-3">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" class="form-control"
                                value="<?php echo htmlspecialchars($formData['address'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" class="form-control"
                                value="<?php echo htmlspecialchars($formData['city'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="postal_code">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" class="form-control"
                                value="<?php echo htmlspecialchars($formData['postal_code'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?php echo isset($formData['id']) ? "/dashboard/users?details=" . $formData['id'] : "/dashboard/users"; ?>"
                        class="btn btn-outline-secondary">Cancel</a>

                    <button type="submit" class="btn btn-primary">
                        <?php echo isset($formData['id']) ? 'Update' : 'Create' ?> User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>