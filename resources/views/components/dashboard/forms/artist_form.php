<?php
$isEdit = ($mode ?? 'create') === 'edit';
?>

<h2><?php echo isset($formData['id']) ? 'Update Artist' : 'Create Artist'; ?></h2>

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
            <form action="/dashboard/artists/<?php echo $isEdit ? 'edit' : 'create'; ?>" method="POST" enctype="multipart/form-data">
                <?php if (isset($formData['id'])) { ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($formData['id']); ?>">
                <?php } ?>

                <!-- Artist Cover -->
                <div class="form-group mt-3">
                    <div class="form-group">
                        <label for="artist_cover">Cover</label>
                        <input type="file" id="artist_cover" name="artist_cover" class="form-control" accept="image/jpeg, image/png">
                    </div>
                </div>

                <!-- Artist Name -->
                <div class="form-group mt-3">
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

<script>
    const coverInput = document.getElementById('artist_cover');
    fillFileInput(coverInput, '<?php echo $formData['cover']; ?>');

    initEditor('preview_description');
    initEditor('main_description');
    initEditor('iconic_albums');
</script>
