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
    <div class="container-custom">
        <h4><i class="bi bi-list"></i> SOLICITAÇÕES</h4>
        
        <form>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Nome do Motorista</label>
                    <input type="text" class="form-control">
                </div>
                <div class="col">
                    <label class="form-label">Placa</label>
                    <input type="text" class="form-control">
                </div>
                <div class="col">
                    <label class="form-label">Rota</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Observações</label>
                <textarea class="form-control" rows="4"></textarea>
            </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-dark">SOLICITAR</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
</body>
</html>
