<?php
include("../solicitacao/conexao.php");

if (isset($_POST["email"]) && isset($_POST["senha"])) {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM usuario WHERE email = ?"; // Consulta o banco atrás de uma correspondencia para o email informado
    $consulta = $conexao->prepare($sql);
    $consulta->bind_param("s", $email);
    $consulta->execute();

    // Obtem o resultado e qtd de linhas da consulta
    $result = $consulta->get_result();
    $qtd_rows = $result->num_rows;

    $user = $result->fetch_assoc();
    $hash = $user["senha"];


    if ($qtd_rows == 1 && password_verify($senha, $hash) && $user["ativo"]) { // Verifica se houve alguma correspondência para o email e se a senha do banco bate com a senha informada
        if (!isset($_SESSION)) session_start(); // Inicia a sessão

        // Salva informações do usuário para uso posterior
        $_SESSION["id"] = $user["id"];
        $_SESSION["nome"] = $user["nome"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["nvl_acesso"] = $user["nvlAcesso"];

        // Salvar token caso o usuario tenha selecionado 'lembre de mim'
        if (isset($_POST["lembrar"])) {
            $token = bin2hex(random_bytes(16));

            $expira = time() + (86400 * 30);
            setcookie("lembrar", $token, $expira, "/", "", true, true);

            $data_expiracao = new DateTime();
            $data_expiracao->modify("+30 days");
            $data_sql = $data_expiracao->format("Y-m-d H:i:s");

            $dados = [
                'user_id' => (int)$user["id"],
                'token' => $token,
                'expira_em' => $data_sql
            ];

            $sql = "INSERT INTO auth_tokens (id_usuario, token, expira) VALUES (?, ?, ?)";
            $consulta = $conexao->prepare($sql);
            $consulta->bind_param("iss", $dados['user_id'], $dados['token'], $dados['expira_em']);
            $consulta->execute();
        }

        echo "<script>
                // Salvar dados do usuário no localStorage para o chat React
                localStorage.setItem('chatUser', JSON.stringify({
                    id: " . $user["id"] . ",
                    nome: '" . addslashes($user["nome"]) . "',
                    email: '" . addslashes($user["email"]) . "'
                }));
                
                alert('Login realizado com sucesso!'); 
                window.location.href='../solicitacao/painel.php';
              </script>";

    } else {
        echo "<script>alert('Erro ao logar, email ou senha incorretos.'); window.location.href='./login.html';</script>";
    }

    $conexao->close();
    $consulta->close();
}
?>

