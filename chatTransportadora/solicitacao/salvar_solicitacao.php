<?php
include("conexao.php");

if (isset($_POST["idSolicitacao"])) {
    $id = $_POST["idSolicitacao"];
    $dataConclusao = date("Y-m-d");

    $sql = "UPDATE tblsolicitacao SET status = 'Concluído', dataConclusao = ? WHERE idSolicitacao = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("si", $dataConclusao, $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
}

$conexao->close();
?>
