<?php
include("conexao.php"); // Conexão com o banco

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pegando os valores do formulário
    $nomeMotorista = $_POST["nomeMotorista"];
    $placaCavalo = $_POST["placaCavalo"];
    $placaCarretas = $_POST["placaCarretas"];
    $destino = $_POST["destino"];
    $valorOperacao = str_replace(',', '.', $_POST["valorOperacao"]);
    $tipoOperacao = $_POST["tipoOperacao"];
    $observacaoSolicitacao = $_POST["observacaoSolicitacao"];

    // Preparando a query para evitar SQL Injection
    $sql = "INSERT INTO tblsolicitacao (nomeMotorista, placaCavalo, placaCarretas, destino, valorOperacao, tipoOperacao, observacaoSolicitacao) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssssss", $nomeMotorista, $placaCavalo, $placaCarretas, $destino, $valorOperacao, $tipoOperacao, $observacaoSolicitacao);

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
