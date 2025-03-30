<?php
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['idSolicitacao'];
    $observacao1 = $_POST['observacaoExtra1'];
    $arquivoNome = "";

    // Verifica se um arquivo foi enviado
    if (!empty($_FILES['arquivoPDF']['name'])) {
        $diretorioUpload = "uploads/";
        if (!is_dir($diretorioUpload)) {
            mkdir($diretorioUpload, 0777, true);
        }

        $arquivoNome = time() . "_" . basename($_FILES['arquivoPDF']['name']);
        $caminhoArquivo = $diretorioUpload . $arquivoNome;

        // Verifica se é um PDF
        $tipoArquivo = strtolower(pathinfo($caminhoArquivo, PATHINFO_EXTENSION));
        if ($tipoArquivo != "pdf") {
            die("Erro: Apenas arquivos PDF são permitidos.");
        }

        if (!move_uploaded_file($_FILES["arquivoPDF"]["tmp_name"], $caminhoArquivo)) {
            die("Erro ao fazer upload do arquivo.");
        }
    }

    // Atualiza a solicitação e marca como concluída
    $sql = "UPDATE tblsolicitacao SET observacaoExtra1 = ?, arquivoPDF = ?, status = 'Concluído', dataConclusao = NOW() WHERE idSolicitacao = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssi", $observacao1, $arquivoNome, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Solicitação concluída!'); window.location.href='solicitacoes.php';</script>";
    } else {
        echo "<script>alert('Erro ao salvar.'); window.location.href='solicitacoes.php';</script>";
    }

    $stmt->close();
    $conexao->close();
}
?>
