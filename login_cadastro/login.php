<?php
    include("../solicitacao/conexao.php");

    if(isset($_POST["email"]) || isset($_POST["senha"])) {
        $email = $_POST["email"];
        $senha = (int) $_POST["senha"];

        $sql = "SELECT * FROM usuario WHERE email = ? AND senha = ?";
        $consulta = $conexao->prepare($sql);
        $consulta->bind_param("si", $email, $senha);
        $consulta->execute() or die("Falha na consulta: " . $conexao->error);


        $result = $consulta->get_result();
        $qtd_rows = $result->num_rows;

        if($qtd_rows == 1) {
            $user = $result->fetch_assoc();

            if(!isset($_SESSION)) session_start();

            $_SESSION["id"] = $user["id"];
            $_SESSION["nome"] = $user["nome"];

            echo "<script>alert('Login realizado com sucesso!'); window.location.href='../solicitacao/painel.php';</script>";
        } else {
            echo "<script>alert('Erro ao logar, email ou senha incorretos.'); window.location.href='./login.php';</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="" method="POST">
        <label>
            Email 
            <input type="email" name="email" required>
        </label>
        <label>
            Senha
            <input type="password" name="senha" required>
        </label>
        <button>Entrar</button>
    </form>
</body>
</html>