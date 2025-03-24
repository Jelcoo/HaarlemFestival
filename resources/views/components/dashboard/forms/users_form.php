<?php

use App\Enum\UserRoleEnum;

$isEdit = ($mode ?? 'create') === 'edit';
?>

<h2><?php echo $isEdit ? 'Update User' : 'Create User'; ?></h2>

<!-- Validation Errors -->
<?php if (!empty($errors)) { ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error) { ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <form action="/dashboard/users/<?php echo $isEdit ? 'edit' : 'create'; ?>" method="POST">

                <?php if (isset($formData['id'])) { ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($formData['id']); ?>">
                <?php } ?>

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
                <?php if (!isset($formData['id'])) { ?>
                    <div class="form-group mt-3">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                <?php } ?>

                <!-- Role Dropdown -->
                <div class="form-group mt-3">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control" required>
                        <?php foreach (UserRoleEnum::cases() as $userRole) { ?>
                            <option value="<?php echo $userRole->value; ?>"
                                <?php echo (isset($formData['role']) && $formData['role'] === $userRole->value) ? 'selected' : ''; ?>>
                                <?php echo ucfirst($userRole->value); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Phone Number -->
                <div class="form-group mt-3">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number" class="form-control"
                        value="<?php echo htmlspecialchars($formData['phone_number'] ?? ''); ?>">
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
                    <a href="/dashboard/users" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <?php echo isset($formData['id']) ? 'Update' : 'Create'; ?> User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
