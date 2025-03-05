<?php

$pages = [
    '/' => 'Home',
    '/dance' => 'Dance!',
    '/yummy' => 'Yummy!',
    '/history' => 'A stroll through history',
    '/magic' => 'Magic@Tylers',
];
?>

<nav class="navbar navbar-expand-lg bg-white shadow-sm p-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="/assets/img/logo.svg" alt="Festival Logo" width="40" height="40">
            <span class="ms-2 festival-logo">THE FESTIVAL</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <?php foreach ($pages as $url => $label) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] === $url) ? 'active' : ''; ?>" href="<?php echo $url; ?>"><?php echo $label; ?></a>
                    </li>
                <?php } ?>
            </ul>
            <a href="#" class="btn btn-custom-yellow ms-3">
                <i class="fa-solid fa-calendar-days"></i> Program
            </a>
            <a href="/login" class="btn btn-custom-green ms-3">
                <i class="fa-solid fa-user"></i> Login
            </a>
        </div>
    </div>
</nav>

<style>
    .festival-logo {
        font-weight: bold;
        color: #C68642; /* Gold */
    }
    .nav-item a {
        color: green;
        font-weight: 500;
        text-decoration: none;
    }
    .nav-item .active {
        color: #0C2D57; /* Dark blue */
        font-weight: bold;
        text-decoration: underline;
    }
</style>
