<?php
session_start();
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["Loginusuario"]) && isset($_POST["senhaUsuario"])) {
        $usuario = $_POST["Loginusuario"];
        $senha = $_POST["senhaUsuario"];

        // Prevenir SQL Injection usando Prepared Statements
        $stmt = $conexao->prepare("SELECT senhaUsuario FROM tblLogin WHERE LoginUsuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $hash_senha = $row["senhaUsuario"];

            // DEBUG: Mostra o hash salvo no banco (REMOVA ISSO EM PRODUÇÃO)
            // echo "Hash no banco: " . $hash_senha . "<br>";

            // Verifica se a senha digitada bate com o hash armazenado
            if (password_verify($senha, $hash_senha)) {
                $_SESSION["Loginusuario"] = $usuario;
                header("Location: painel.php");
                exit();
            } else {
                echo "<script>alert('Senha incorreta!');window.location.href='paginaLogin.php';</script>";
            }
        } else {
            echo "<script>alert('Usuário não encontrado!');window.location.href='paginaLogin.php';</script>";
        }

        // Fechar statement
        $stmt->close();
    } else {
        echo "Erro: Campos de login não foram recebidos.";
    }
}
?>
