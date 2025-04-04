<?php

$pages = [
    '/' => 'Home',
    '/dance' => 'DANCE!',
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
                        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] === $url) ? 'active' : ''; ?>"
                            href="<?php echo $url; ?>"><?php echo $label; ?></a>
                    </li>
                <?php } ?>
            </ul>

            <a href="/cart" class="btn btn-custom-yellow ms-lg-3">
                <i class="fa-solid fa-cart-shopping"></i> Cart
            </a>
            <?php if (App\Application\Session::isValidSession()) { ?>
                <a href="/account/manage" class="btn btn-custom-yellow ms-3">
                    <i class="fa-solid fa-user"></i> Account
                </a>
            <?php } else { ?>
                <a href="/login" class="btn btn-custom-green ms-3">
                    <i class="fa-solid fa-user"></i> Login
                </a>
            <?php } ?>
        </div>
    </div>
</nav>