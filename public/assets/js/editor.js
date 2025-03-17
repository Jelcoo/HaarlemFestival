function initEditor(elementId) {
    tinymce.init({
        selector: `#${elementId}`,
        license_key: 'gpl',
        promotion: false,

        height: 400,

        menubar: true,
        plugins: 'anchor autolink charmap codesample emoticons image link lists media preview searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',

        newline_behavior: 'linebreak',
    });
}
