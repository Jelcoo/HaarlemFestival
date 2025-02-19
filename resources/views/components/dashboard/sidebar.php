<nav class="col-lg-2 bg-dark text-white vh-100 p-3">
    <h4 class="text-center">Dashboard</h4>
    <ul class="nav flex-column">
        <?php foreach ($sidebarItems as $key => $item): ?>
            <li class="nav-item">
                <a href="<?= $item['url'] ?>"
                    class="nav-link text-white <?= ($activePage === $key) ? 'bg-primary text-light' : '' ?>">
                    <?= $item['label'] ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>