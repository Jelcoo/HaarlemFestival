<div class="container">
    <h1>Manage account</h1>
    <?php include __DIR__ . '/../../components/errordisplay.php'; ?>
    <?php /** @var \App\Models\User $user  */ ?>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h3>Account details</h3>
            <form action="/account/manage" method="POST" class="d-flex gap-2 flex-column">
                <div class="form-group">
                    <label for="firstname">First name</label>
                    <input type="text" class="form-control" name="firstname" placeholder="Enter first name" <?php echo isset($fields['firstname']) ? 'value="' . $fields['firstname'] . '"' : 'value="' . $user->firstname . '"'; ?>>
                </div>
                <div class="form-group">
                    <label for="lastname">Last name</label>
                    <input type="text" class="form-control" name="lastname" placeholder="Enter last name" <?php echo isset($fields['lastname']) ? 'value="' . $fields['lastname'] . '"' : 'value="' . $user->lastname . '"'; ?>>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Enter email" <?php echo isset($fields['email']) ? 'value="' . $fields['email'] . '"' : 'value="' . $user->email . '"'; ?>>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address" placeholder="Enter address example: Street 1"
                        <?php echo isset($fields['address']) ? 'value="' . $fields['address'] . '"' : 'value="' . $user->address . '"'; ?>>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" class="form-control" name="city" placeholder="Enter city example: Amsterdam"
                        <?php echo isset($fields['city']) ? 'value="' . $fields['city'] . '"' : 'value="' . $user->city . '"'; ?>>
                </div>
                <div class="form-group">
                    <label for="postal_code">Postal code</label>
                    <input type="text" class="form-control" name="postal_code"
                        placeholder="Enter postal code example: 1111AA" <?php echo isset($fields['postal_code']) ? 'value="' . $fields['postal_code'] . '"' : 'value="' . $user->postal_code . '"'; ?>>
                </div>

                <button type="submit" class="btn btn-primary w-50 align-self-center">Update account</button>
            </form>
        </div>
        <div class="col-sm-12 col-md-6">
            <h3>Change password</h3>
            <form action="/account/manage/password" method="POST" class="d-flex gap-2 flex-column">
                <div class="form-group">
                    <label for="firstname">Current password</label>
                    <input type="password" class="form-control" name="currentPassword"
                        placeholder="Enter current password" <?php echo isset($fields['currentPassword']) ? 'value="' . $fields['currentPassword'] . '"' : 'value=""'; ?>>
                </div>
                <div class="form-group">
                    <label for="firstname">New password</label>
                    <input type="password" class="form-control" name="newPassword" placeholder="Enter first name" <?php echo isset($fields['newPassword']) ? 'value="' . $fields['newPassword'] . '"' : 'value=""'; ?>>
                </div>
                <div class="form-group">
                    <label for="lastname">Confirm new password</label>
                    <input type="password" class="form-control" name="confirmNewPassword" placeholder="Enter last name"
                        <?php echo isset($fields['confirmNewPassword']) ? 'value="' . $fields['confirmNewPassword'] . '"' : 'value=""'; ?>>
                </div>

                <button type="submit" class="btn btn-primary w-50 align-self-center">Update password</button>
            </form>
        </div>
    </div>
</div>