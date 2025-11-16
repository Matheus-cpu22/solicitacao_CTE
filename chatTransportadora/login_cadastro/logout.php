<?php
    if(!isset($_SESSION)) session_start();

    session_destroy();

    setcookie("lembrar", "", time() - (86400 * 30), "/");
    setcookie("id_usuario", "", time() - (86400 * 30), "/");
    unset($_COOKIE['user_preference']);
    unset($_COOKIE['id_usuario']);

    header("Location: login.html");
?>