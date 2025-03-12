<nav class="col-lg-2 bg-light text-black min-vh-100 p-3">
    <h4 class="text-center">Dashboard</h4>
    <ul class="nav flex-column">
        <?php foreach ($sidebarItems as $key => $item) { ?>
            <li class="nav-item">
                <a href="<?php echo $item['url']; ?>"
                    class="nav-link text-black <?php echo ($activePage === $key) ? 'bg-primary text-white' : ''; ?>">
                    <?php echo $item['label']; ?>
                </a>
            </li>
        <?php } ?>
    </ul>
</nav>
