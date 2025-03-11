<?php require_once __DIR__ . '/../../components/navbar.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require __DIR__ . '/../../components/dashboard/sidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="col-lg-10 p-4">
            <?php echo $content ?? 'Error loading page content'; ?>
        </main>
    </div>
</div>
