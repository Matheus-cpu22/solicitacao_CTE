$(document).ready(function () {
    $('#tabelaSolicitacoes').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        order: [[0, 'desc']] // Ordena por ID decrescente
    });
});
