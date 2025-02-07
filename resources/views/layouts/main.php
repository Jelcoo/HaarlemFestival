<?php

use App\Application\Session;

$loggedIn = Session::isValidSession();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Festival</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/assets/css/style.css">

    <script src=" https://cdn.jsdelivr.net/npm/sweetalert2@11.15.3/dist/sweetalert2.all.min.js "></script>
    <link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.15.3/dist/sweetalert2.min.css " rel="stylesheet">

    <script src="/assets/js/utils.js"></script>
</head>

<body>
    <?php
        if (App\Config\Config::getKey('APP_ENV') === 'development') {
            $endtime = microtime(true);
            printf('&centerdot; Page loaded in %f seconds', $endtime - $GLOBALS['APP_START_TIME']);
        } ?>
</body>

</html>
