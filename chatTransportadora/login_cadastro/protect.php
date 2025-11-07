<?php
     include("../solicitacao/conexao.php");

     // Icluir este arquivo nos que nescessitam de login
     if(!isset($_SESSION)) session_start();

     if(isset($_COOKIE["lembrar"])) {
          $token = $_COOKIE["lembrar"];

          $sql = "SELECT id_usuario FROM auth_tokens WHERE token = ? AND expira > NOW()";
          $consulta = $conexao->prepare($sql);
          $consulta->bind_param("s", $token);
          $consulta->execute();

          $result = $consulta->get_result();
          $qtd_rows = $result->num_rows;
          $user = $result->fetch_assoc();

          $id_user = (int)$user["id_usuario"];

          if ($qtd_rows > 0) {
               $sql = "SELECT * FROM usuario WHERE id = ?"; 
               $consulta = $conexao->prepare($sql);
               $in = 7;
               $consulta->bind_param("i", $id_user);

               $consulta->execute();

               // Obtem o resultado e qtd de linhas da consulta
               $result = $consulta->get_result();
               $user = $result->fetch_assoc();

               echo $user["nome"];

               $_SESSION["id"] = $user["id"];
               $_SESSION["nome"] = $user["nome"];
               $_SESSION["email"] = $user["email"];
               $_SESSION["nvl_acesso"] = $user["nvlAcesso"];
          }
          $conexao->close();
          $consulta->close();
     }

     if(!isset($_SESSION["id"])) die("É necessário estar logado para acessar essa página! <a href='../login_cadastro/login.html'>Logar</a>")
?>