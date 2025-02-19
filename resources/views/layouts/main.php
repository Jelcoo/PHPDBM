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
    <title>PHPDBM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/assets/css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="/assets/js/bootstrap-theme.js"></script>
    <script>
        window.addEventListener("DOMContentLoaded", () => {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        });
    </script>

    <script src=" https://cdn.jsdelivr.net/npm/sweetalert2@11.15.3/dist/sweetalert2.all.min.js "></script>
    <link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.15.3/dist/sweetalert2.min.css " rel="stylesheet">

    <script src="/assets/js/utils.js"></script>
    <script src="/assets/js/tableutils.js"></script>
</head>

<body class="container">
    <div>
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <span class="fs-4">PHPDBM</span>
            </a>

            <ul class="nav nav-pills">
                <?php if ($loggedIn) { ?>
                    <li class="nav-item"><a href="/logout" class="nav-link">Logout</a></li>
                <?php } else { ?>
                    <li class="nav-item"><a href="/login" class="nav-link">Login</a></li>
                <?php } ?>
            </ul>
        </header>
    </div>
    <div>
        {{content}}
    </div>
    <footer class="py-3 my-4">
        <p class="text-center text-body-secondary">© <?php echo date('Y'); ?> Jelco <?php
                                                                                                if (App\Config\Config::getKey('APP_ENV') === 'development') {
                                                                                                    $endtime = microtime(true);
                                                                                                    printf('&centerdot; Page loaded in %f seconds', $endtime - $GLOBALS['APP_START_TIME']);
                                                                                                } ?>
        </p>
    </footer>
</body>

</html>
