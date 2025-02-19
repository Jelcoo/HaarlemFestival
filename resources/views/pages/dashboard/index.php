<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../components/dashboard/sidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="col-lg-10 p-4">
            <?= $content ?? 'Error loading page content'; ?>
        </main>
    </div>
</div>