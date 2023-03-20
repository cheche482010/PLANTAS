$.ajax({
    type: "POST",
    url: BASE_URL + 'Plantas/Consulta',
}).done(function(datos) {
    var data = JSON.parse(datos);
    $("#example1").DataTable({
        "data": data,
        "columns": [{
            "data": "nombre_comun"
        }, {
            "data": "nombre_cientifico"
        }, {
            "data": "descripcion"
        }, {
            data: function(data) {
                return ('<td class="text-center">' + '<a href="javascript:void(0)" style="margin-right: 5px;background: #4dbdbd !important;" class="btn bg-info ver-popup" title="Ver" type="button" data-toggle="modal" data-target="#ver">' + '<i class="fa fa-eye"></i>' + "</a>" + "</td>");
            },
        }],
        "responsive": true,
        "autoWidth": false,
        "ordering": true,
        "info": true,
        "processing": true,
        "pageLength": 10,
        "lengthMenu": [5, 10, 20, 30, 40, 50, 100],
        "buttons": ["copy", "excel", "pdf", "print", "colvis"]
    }).container().appendTo('#example1_wrapper .col-md-6:eq(0)');
}).fail(function() {
    alert("error");
});