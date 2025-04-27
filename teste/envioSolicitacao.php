<?php
include("conexao.php"); // Conexão com o banco

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pegando os valores do formulário
    $nomeMotorista = $_POST["nomeMotorista"];
    $placaCaminhao = $_POST["placaCaminhao"];
    $rotaCaminhao = $_POST["rotaCaminhao"];
    $observacaoSolicitacao = $_POST["observacaoSolicitacao"];

    // Preparando a query para evitar SQL Injection
    $sql = "INSERT INTO tblsolicitacao (nomeMotorista, placaCaminhao, rotaCaminhao, observacaoSolicitacao) 
            VALUES (?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssss", $nomeMotorista, $placaCaminhao, $rotaCaminhao, $observacaoSolicitacao);

    // Executa a query e verifica se deu certo
    if ($stmt->execute()) {
        echo "<script>alert('Solicitação enviada com sucesso!'); window.location.href='painel.php';</script>";
    } else {
        echo "<script>alert('Erro ao enviar solicitação!'); window.location.href='painel.php';</script>";
    }

    // Fecha a conexão
    $stmt->close();
    $conexao->close();
} else {
    echo "<script>alert('Acesso inválido!'); window.location.href='painel.php';</script>";
}
?>
