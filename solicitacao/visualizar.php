<?php
include("conexao.php");

// Consulta SQL
$sql = "SELECT * FROM tblsolicitacao";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Solicitações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/vizualizar.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card p-4">
            <h2 class="text-center mb-4">Solicitações Registradas</h2>
            <div class="table-responsive">
                <table id="tabelaSolicitacoes" class="table table-hover text-center align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Motorista</th>
                            <th>Placa Cavalo</th>
                            <th>Placa Carreta(s)</th>
                            <th>Destino</th>
                            <th>Valor</th>
                            <th>Tipo</th>
                            <th>Observação</th>
                            <th>Data</th>
                            <th>PDF</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nomeMotorista']) ?></td>
                            <td><?= htmlspecialchars($row['placaCavalo']) ?></td>
                            <td><?= htmlspecialchars($row['placaCarretas']) ?></td>
                            <td><?= htmlspecialchars($row['destino']) ?></td>
                            <td>R$ <?= number_format($row['valorOperacao'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['tipoOperacao']) ?></td>
                            <td class="observacao"><?= htmlspecialchars($row['observacaoSolicitacao']) ?></td>
                            <td><?= htmlspecialchars($row['dataSolicitacao']) ?></td>
                            <td>
                                <?php if ($row['arquivoPDF']) { ?>
                                    <a href="/TCIprojeto/solicitacao_CTE/solicitacao/uploads/<?= $row['arquivoPDF'] ?>" class="btn btn-outline-secondary btn-sm" target="_blank">Baixar</a>

                                <?php } else { ?>
                                    <span class="text-muted">Nenhum</span>
                                <?php } ?>
                            </td>
                            <td>
                            <?php if ($row['status'] == 'Pendente') { ?>
    <form action="salvar_observacao.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <input type="text" name="observacaoExtra1" class="form-control form-control-sm mb-2" placeholder="Nova Observação">
        <input type="file" name="arquivoPDF" accept=".pdf" class="form-control form-control-sm mb-2">
        <button type="submit" class="btn btn-success btn-sm">Salvar</button>
    </form>
<?php } else { ?>
    <div class="form-control form-control-sm mb-2" style="background-color: #f1f1f1;">
        <?= nl2br(htmlspecialchars($row['observacaoExtra'])) ?>
    </div>
<?php } ?>

                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="js/vizualizar.js"></script>
</body>
</html>

<?php $conexao->close(); ?>
