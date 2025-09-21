<?php
     if(!isset($_SESSION)) session_start();

     if(!isset($_SESSION["id"])) die("É necessário estar logado para acessar essa página! <a href='login.php'>Logar</a>")
?>