<?php
    session_start();

    $_SESSION = [];

    session_destroy();

    header("Location: ../../Front-End/HTML/informacion.html");

    exit();
?>