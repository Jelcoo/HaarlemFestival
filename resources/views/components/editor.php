<script src="https://cdn.jsdelivr.net/npm/tinymce@7.7.0/tinymce.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tinymce@7.7.0/skins/ui/oxide/content.min.css">

<textarea id="editor"><?php echo $content ?? ''; ?></textarea>

<script>
    tinymce.init({
        selector: '#editor',
        license_key: 'gpl',
        promotion: false,

        height: 400,

        menubar: true,
        plugins: 'anchor autolink charmap codesample emoticons image link lists media preview searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',

        forced_root_block: false,
        br_newlines: true,
    });
</script>
