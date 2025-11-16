<?php

     // Verifica se existe um token válido para logar o usuário automaticamente, caso ele tenha selecionado a opção "lembrar de mim" anteriormente
     function validaManterLogado() {
          include("../solicitacao/conexao.php");

          if (isset($_COOKIE["lembrar"])) {
               $token = $_COOKIE["lembrar"];
               $id_user = (int)$_COOKIE["id_usuario"];

               $sql = "SELECT * FROM auth_tokens WHERE id_usuario = ? AND expira > NOW()";
               $consulta = $conexao->prepare($sql);
               $consulta->bind_param("i", $id_user);
               $consulta->execute();

               $result = $consulta->get_result();
               $qtd_rows = $result->num_rows;

               if ($qtd_rows > 0) {
                    while ($user = $result->fetch_assoc()) {
                         if (password_verify($token, $user["token"])) {
                              $sql = "SELECT * FROM usuario WHERE id = ?";
                              $consulta = $conexao->prepare($sql);
                              $in = 7;
                              $consulta->bind_param("i", $id_user);

                              $consulta->execute();

                              // Obtém o resultado e qtd de linhas da consulta
                              $result = $consulta->get_result();
                              $user = $result->fetch_assoc();

                              $_SESSION["id"] = $user["id"];
                              $_SESSION["nome"] = $user["nome"];
                              $_SESSION["email"] = $user["email"];
                              $_SESSION["nvl_acesso"] = $user["nvlAcesso"];
                         }
                    }
               }
               $conexao->close();
               $consulta->close();
          }
     }

?>