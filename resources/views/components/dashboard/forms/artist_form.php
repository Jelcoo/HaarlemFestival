<h2><?php echo isset($formData['id']) ? 'Update Artist' : 'Create Artist'; ?></h2>

<!-- Status message -->
<?php if (!empty($status['message'])) { ?>
<div class="alert alert-<?php echo $status['status'] ? 'success' : 'danger'; ?>">
    <?php echo htmlspecialchars($status['message']); ?>
</div>
<?php } ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <form action="/dashboard/artists" method="POST">
                <input type="hidden" name="action"
                    value="<?php echo isset($formData['id']) ? 'update' : 'createArtist'; ?>">

                <?php if (isset($formData['id'])) { ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($formData['id']); ?>">
                <?php } ?>

                <!-- Artist Name -->
                <div class="form-group">
                    <label for="name">Artist Name</label>
                    <input type="text" id="name" name="name" class="form-control" required
                        value="<?php echo htmlspecialchars($formData['name'] ?? ''); ?>">
                </div>

                <!-- Preview Description -->
                <div class="form-group mt-3">
                    <label for="preview_description">Preview Description</label>
                    <textarea id="preview_description" name="preview_description"
                        class="form-control"><?php echo htmlspecialchars($formData['preview_description'] ?? ''); ?></textarea>
                </div>

                <!-- Main Description -->
                <div class="form-group mt-3">
                    <label for="main_description">Main Description</label>
                    <textarea id="main_description" name="main_description"
                        class="form-control"><?php echo htmlspecialchars($formData['main_description'] ?? ''); ?></textarea>
                </div>

                <!-- Iconic Albums -->
                <div class="form-group mt-3">
                    <label for="iconic_albums">Iconic Albums</label>
                    <textarea id="iconic_albums" name="iconic_albums"
                        class="form-control"><?php echo htmlspecialchars($formData['iconic_albums'] ?? ''); ?></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?php echo isset($formData['id']) ? '/dashboard/artists?details=' . $formData['id'] : '/dashboard/artists'; ?>"
                        class="btn btn-outline-secondary">Cancel</a>

                    <button type="submit" class="btn btn-primary">
                        <?php echo isset($formData['id']) ? 'Update' : 'Create'; ?> Artist
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
