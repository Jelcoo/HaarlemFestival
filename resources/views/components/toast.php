<div class="toast-container">
    <div class="toast align-items-center text-bg-success border-0" id="successToast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <?php echo $_GET['message'] ?? 'This is a toast'; ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close" id="closeToast"></button>
        </div>
    </div>
</div>

<style>
    .toast-container {
        position: fixed;
        bottom: 1rem;
        right: 1rem;
        z-index: 1050;
    }

    .toast-body {
        font-size: 1.25rem;
    }
</style>

<script>
    document.getElementById("closeToast").addEventListener("click", function() {
        const url = new URL(window.location);
        url.searchParams.delete("message");
        history.replaceState({}, document.title, url.pathname);
    });
</script>
