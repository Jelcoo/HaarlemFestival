<nav class="col-lg-2 bg-light text-black p-3 admin-sidebar">
    <!-- Title -->
    <h4 class="text-center">
        <a href="/dashboard" class="text-decoration-none">Dashboard</a>
    </h4>

    <!-- Navigation -->
    <ul class="nav flex-column">
        <?php foreach ($sidebarItems as $key => $item) {
            $isNested = array_filter($item, 'is_array');
            ?>
            <?php if (!empty($isNested)) { ?>
                <li class="nav-item">
                    <!-- Parent Label -->
                    <span class="text-muted fw-bold"><?php echo $item['label']; ?></span>

                    <!-- Nested Items -->
                    <ul class="nav flex-column ms-3">
                        <?php foreach ($item as $nestedKey => $nestedItem) {
                            if (!is_array($nestedItem) || $nestedKey === 'label') {
                                continue;
                            } ?>
                            <li class="nav-item">
                                <a href="<?php echo $nestedItem['url']; ?>"
                                   class="nav-link text-black <?php echo ($activePage === $nestedKey) ? 'bg-primary text-white' : ''; ?>">
                                    <?php echo $nestedItem['label']; ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } else { ?>
                <li class="nav-item">
                    <a href="<?php echo $item['url']; ?>"
                       class="nav-link text-black <?php echo ($activePage === $key) ? 'bg-primary text-white' : ''; ?>">
                        <?php echo $item['label']; ?>
                    </a>
                </li>
            <?php } ?>

        <?php } ?>
    </ul>
</nav>

<style>
    @media screen and (min-width: 768px) {
        .admin-sidebar {
            min-height: 100vh;
        }
    }
</style>
