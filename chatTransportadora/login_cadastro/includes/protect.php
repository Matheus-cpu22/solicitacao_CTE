<?php
     include("../solicitacao/conexao.php");
     include(__DIR__ . "/helpers.php");

     // Incluir este arquivo nos que necessitam de login
     if(!isset($_SESSION)) session_start();

     validaManterLogado();

     if(!isset($_SESSION["id"])) die("É necessário estar logado para acessar essa página! <a href='../login_cadastro/login.html'>Logar</a>")
?>