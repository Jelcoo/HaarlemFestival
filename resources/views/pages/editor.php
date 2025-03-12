<div class="container">
    <div class="alert alert-success d-none" role="alert"></div>

    <?php include __DIR__ . '/../components/errordisplay.php'; ?>

    <?php include_once __DIR__ . '/../components/editor.php'; ?>
    <button id="editorSubmit" type="submit" class="btn btn-primary">Save</button>
</div>

<script>
    const saveButton = document.getElementById('editorSubmit');

    saveButton.addEventListener('click', () => {
        const editorContent = tinymce.get('editor').getContent();

        fetch('/editor', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ content: editorContent })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('.alert-success').classList.remove('d-none');
                    document.querySelector('.alert-success').textContent = data.message;
                }
            });
    });
</script>
