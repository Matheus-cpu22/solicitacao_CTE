<?php
include("conexao.php");

// Obtém os dados da tabela
$sql = "SELECT * FROM tblsolicitacao";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Solicitações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Solicitações Registradas</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Motorista</th>
                    <th>Placa</th>
                    <th>Rota</th>
                    <th>Observação Inicial</th>
                    <th>Nova Observação</th>
                    <th>Arquivo PDF</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['idSolicitacao'] ?></td>
                    <td><?= htmlspecialchars($row['nomeMotorista']) ?></td>
                    <td><?= htmlspecialchars($row['placaCaminhao']) ?></td>
                    <td><?= htmlspecialchars($row['rotaCaminhao']) ?></td>
                    <td><?= htmlspecialchars($row['observacaoSolicitacao']) ?></td>
                    <td><?= htmlspecialchars($row['observacaoExtra1']) ?></td>
                    <td>
                        <?php if ($row['arquivoPDF']) { ?>
                            <a href="uploads/<?= $row['arquivoPDF'] ?>" target="_blank">Baixar</a>
                        <?php } else { ?>
                            Nenhum arquivo
                        <?php } ?>
                    </td>
                    <td>
                        <form action="salvar_observacao.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="idSolicitacao" value="<?= $row['idSolicitacao'] ?>">
                            <input type="text" name="observacaoExtra1" class="form-control mb-2" placeholder="Nova Observação 1">
                            <input type="file" name="arquivoPDF" accept=".pdf" class="form-control mb-2">
                            <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conexao->close(); ?>
