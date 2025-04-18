<?php

use App\Config\Config;
use App\Application\Session;

$loggedIn = Session::isValidSession();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">

    <title>The Festival</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/assets/css/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script>
        window.addEventListener("DOMContentLoaded", () => {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        });
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
    <script src="/assets/js/utils.js"></script>
    <script src="/assets/js/order.js"></script>
</head>

<body>
    <div class="full-container">
        <?php include __DIR__ . '/../components/navbar.php'; ?>
        {{content}}
        <?php include __DIR__ . '/../components/footer.php'; ?>
        <?php
        if (Config::getKey('APP_ENV') === 'development') {
            $endtime = microtime(true);
            printf('Page loaded in %f seconds - Query count: %d', $endtime - $GLOBALS['APP_START_TIME'], $GLOBALS['QUERY_COUNT']);
        } ?>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="socialMediaModal" tabindex="-1" aria-labelledby="socialMediaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="socialMediaModalLabel">Share your experience!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex gap-3 flex-row justify-content-center share-buttons">
                    <a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode(Config::getKey('APP_URL') . $_SERVER['REQUEST_URI']); ?>">
                        <img src="/assets/img/icons/facebook.svg" />
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(Config::getKey('APP_URL') . $_SERVER['REQUEST_URI']) . '&text=' . urlencode('Check out this event!'); ?>">
                        <img src="/assets/img/icons/twitter.svg" />
                    </a>
                    <a href="https://www.instagram.com/?url=<?php echo urlencode(Config::getKey('APP_URL') . $_SERVER['REQUEST_URI']); ?>">
                        <img src="/assets/img/icons/instagram.svg" />
                    </a>
                    <a href="https://www.reddit.com/submit?url=<?php echo urlencode(Config::getKey('APP_URL') . $_SERVER['REQUEST_URI']) . '&title=' . urlencode('Check out this event!'); ?>">
                        <img src="/assets/img/icons/reddit.svg" />
                    </a>
                    <a href="https://t.me/share/url?url=<?php echo urlencode(Config::getKey('APP_URL') . $_SERVER['REQUEST_URI']) . '&text=' . urlencode('Check out this event!'); ?>">
                        <img src="/assets/img/icons/telegram.svg" />
                    </a>
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('Check out this event!') . '%0a%0a' . urlencode(Config::getKey('APP_URL') . $_SERVER['REQUEST_URI']); ?>">
                        <img src="/assets/img/icons/whatsapp.svg" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>