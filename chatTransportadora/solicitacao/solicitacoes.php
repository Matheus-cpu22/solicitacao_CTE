<?php
include("conexao.php");
include("../login_cadastro/includes/protect.php");

// Obtém as solicitações agrupadas por status
$sqlPendentes = "SELECT * FROM tblsolicitacao WHERE status = 'Pendente' ORDER BY id DESC";
$sqlConcluidas = "SELECT * FROM tblsolicitacao WHERE status = 'Concluído' ORDER BY id DESC";

$resultPendentes = $conexao->query($sqlPendentes);
$resultConcluidas = $conexao->query($sqlConcluidas);
?>

<script>
  function showModal(content) {
    const modal = document.getElementById("modal");
    const modalContent = document.getElementById("modal-content");
    modalContent.innerHTML = content;
    modal.style.display = "flex";
  }

  function closeModal() {
    document.getElementById("modal").style.display = "none";
  }
</script>

<div class="modal" id="modal" onclick="closeModal()">
  <div class="modal-content" onclick="event.stopPropagation()">
    <button class="close-btn" onclick="closeModal()">X</button>
    <div id="modal-content"></div>
  </div>
</div>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/solicitacoes.css">
    <link rel="stylesheet" href="css/pop-up.css">
    <title>Solicitações</title>
</head>

<body>
    <div class="container">
        <h2>Solicitações Pendentes</h2>
        <div class="table-container"></div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Motorista</th>
                    <th>Placa Cavalo</th>
                    <th>Placa Carreta</th>
                    <th>Destino</th>
                    <th>Valor da Operação</th>
                    <th>Tipo de Operação</th>
                    <th>Observação</th>
                    <th>Status</th>
                    <th>Data da Solicitação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultPendentes->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nomeMotorista']) ?></td>
                    <td><?= htmlspecialchars($row['placaCavalo']) ?></td>
                    <td><?= htmlspecialchars($row['placaCarretas']) ?></td>
                    <td><?= htmlspecialchars($row['destino']) ?></td>
                    <td>R$ <?= htmlspecialchars($row['valorOperacao']) ?></td>
                    <td><?= htmlspecialchars($row['tipoOperacao']) ?></td>
                    <td class="observacao"><?= htmlspecialchars($row['observacaoSolicitacao']) ?></td>
                    <td class="status-pendente">Pendente</td>
                    <td><?= !empty($row['dataSolicitacao']) ? date("d/m/Y H:i:s", strtotime($row['dataSolicitacao'])) : '-' ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Solicitações Concluídas</h2>
        <table>
            <thead>
                <tr>
                <th>ID</th>
                    <th>Motorista</th>
                    <th>Placa Cavalo</th>
                    <th>Placa Carreta</th>
                    <th>Destino</th>
                    <th>Valor da Operação</th>
                    <th>Tipo de Operação</th>
                    <th>Observação</th>
                    <th>Observação Extra</th>
                    <th>Status</th>
                    <th>Anexo</th>
                    <th>Data da Solicitação</th>
                    <th>Data da Conclusão</th>
               <!-- <th>Corrigir</th> -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultConcluidas->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nomeMotorista']) ?></td>
                    <td><?= htmlspecialchars($row['placaCavalo']) ?></td>
                    <td><?= htmlspecialchars($row['placaCarretas']) ?></td>
                    <td><?= htmlspecialchars($row['destino']) ?></td>
                    <td>R$ <?= htmlspecialchars($row['valorOperacao']) ?></td>
                    <td><?= htmlspecialchars($row['tipoOperacao']) ?></td>
                    <td class="expandable-td" onclick="showModal('<?= htmlspecialchars($row['observacaoSolicitacao']) ?>')"><?= htmlspecialchars($row['observacaoSolicitacao']) ?></td>
                    <td class="expandable-td" onclick="showModal('<?= htmlspecialchars($row['observacaoExtra']) ?>')"><?= htmlspecialchars($row['observacaoExtra']) ?></td>

                    <td class="status-concluido">Concluído</td>
                    <td>
                        <?php if (!empty($row['arquivoPDF'])) { ?>
                            <a class="download-btn" href="uploads/<?= $row['arquivoPDF'] ?>" target="_blank">Baixar</a>
                        <?php } else { echo "Nenhum"; } ?>
                    </td>
                    <td><?= date("d/m/Y H:i:s", strtotime($row['dataSolicitacao'] ?? '')) ?></td>
                    <td><?= date("d/m/Y H:i:s", strtotime($row['dataConclusao'] ?? '')) ?></td>
               <!-- <td><a href=""><img src="css/erro.png" alt="" width="100%" height="100%"></a></td> -->
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>

</html>

<?php $conexao->close(); ?>
