<?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header('location:login.php?erro=2');
        die;
    }

    $usuario = $_SESSION['user'];
    ?>