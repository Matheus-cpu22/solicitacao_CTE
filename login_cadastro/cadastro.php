<?php
    include("../solicitacao/conexao.php");
    include("../login_cadastro/protect.php");

    if(isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["senha"])) {
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $senha = $_POST["senha"];

        $sql = "SELECT * FROM usuario WHERE email = ?";
        $consulta = $conexao->prepare($sql);
        $consulta->bind_param("s", $email);
        $consulta->execute();

        $result = $consulta->get_result();
        $qtd_rows = $result->num_rows;

        if($qtd_rows == 0) {
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)";
            $consulta = $conexao->prepare($sql);
            $consulta->bind_param("sss", $nome, $email, $hash);
            if($consulta->execute()) {
                echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='./cadastro.php';</script>";
            } else {
                echo "<script>alert('ERRO!'); window.location.href='./cadastro.php';</script>";
            }

        } else {
            echo "<script>alert('Erro ao cadastrar, já existe um usuário com esse email.'); window.location.href='./cadastro.php';</script>";
        }

        $conexao->close();
        $consulta->close();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
</head>
<body>
    <form action="" method="POST">
        <label>
            Nome: 
            <input name="nome" required>
        </label>
        <label>
            Email: 
            <input type="email" name="email" required>
        </label>
        <label>
            Senha: 
            <input type="password" name="senha" required>
        </label>
        <button>Criar</button>
    </form>
</body>
</html>