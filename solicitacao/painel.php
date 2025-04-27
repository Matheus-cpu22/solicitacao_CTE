<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .container-custom {
            max-width: 900px;
            margin: auto;
            border: 2px solid black;
            padding: 20px;
            margin-top: 30px;
        }
        .upload-box {
            border: 2px solid black;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 50px;
            cursor: pointer;
        }
        .upload-box i {
            font-size: 24px;
        }
    </style>
</head>
<body>
<nav class="navbar bg-body-tertiary fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Solicitar Documentação</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Transportadora Cidade Imperial</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="http://localhost/tciprojeto/teste/solicitacoes.php">Solicitações</a>
          </li>
        </ul>
        </form>
      </div>
    </div>
  </div>
</nav>
<div style="margin-top: 100px;">
    <div class="container-custom">
        <h4><i class="bi bi-list"></i> SOLICITAÇÕES</h4>
        
        <form action="envioSolicitacao.php" method="POST">
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Nome do Motorista</label>
            <input type="text" name="nomeMotorista" class="form-control" required>
        </div>
        <div class="col">
            <label class="form-label">Placa do Cavalo</label>
            <input name="placaCavalo" type="text" class="form-control" required>
        </div>
        <div class="col">
            <label class="form-label">Placa das carretas</label>
            <input name="placaCarretas" type="text" class="form-control" required>
        </div>
        <div class="row mb-3">

        <div class="col">
            <label class="form-label">Destino da carga</label>
            <input name="destino" type="text" class="form-control" required>
        </div>
        <div class="col">
            <label class="form-label">Valor da operação</label>
            <input name="valorOperacao" type="text" class="form-control" required>
        </div>
        <div>
        <div class="col">
    <label class="form-label">Operação</label><br>
    <input type="radio" name="tipoOperacao" id="CTE" value="CTE" checked> CT-e <br>
    <input type="radio" name="tipoOperacao" id="MDFE" value="MDFE"> MDF-e <br>
    <input type="radio" name="tipoOperacao" id="CTE-MDFE" value="CTE-MDFE"> CT-e E MDF-e <br>
</div>
    </div>
    </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Observações</label>
        <textarea name="observacaoSolicitacao" class="form-control" rows="4" required></textarea>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-dark">SOLICITAR</button>
    </div>
</form>

    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
</body>
</html>
