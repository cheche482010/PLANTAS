window.onload = function(){
var chat = document.getElementById('chat');
var user_input = document.getElementById('user-input');
var caracteristicas = [];
var coincidencias = [];

first_message();

function first_message(){
    cargar_mensaje('Hola!',1);
    setTimeout(function(){
        cargar_mensaje('Intentare adivinar la planta en la que piensas, ¿estás preparado(a)?',1);
        var button = document.createElement('button');
        button.className = 'btn btn-info mx-auto';
        button.type = 'button';
        button.innerText = 'Vamos!';
        button.onclick = function(){
            user_input.innerHTML = '';
            cargar_mensaje(`Vamos!`,0);
            setTimeout(function(){
                ask_question();
            },2000);
        }
        setTimeout(function(){
            user_input.querySelector('div').appendChild(button);
        },3000);
    },2500);
}

function cargar_mensaje(mensaje,tipo){
    var div = document.createElement('div');
    tipo == 1 ? div.className = 'loader loader-ia' : div.className = 'loader loader-user';
    var time = 2500;
    chat.appendChild(div);
    chat.scrollTop = chat.scrollHeight + 50;
    tipo == 1? time = 2500 : time = 800;
    setTimeout(function(){
        for (var i = 0; i < chat.children.length; i++){
            if(chat.children[i] == div){
                tipo == 1? div.className = 'ia' : div.className = 'user';
                div.innerText = mensaje;
            }
        }
    },time);
}

function ask_question(){
    $.ajax({
        type: "POST",
        url: BASE_URL + 'Plantas/get_question',
        data: {'caracteristicas' : caracteristicas}
    }).done(function(result){
          var datos = JSON.parse(result);
          console.log(caracteristicas);
          console.log(JSON.parse(datos['resp']));
          var btn_si = document.createElement('button');
          var btn_no = document.createElement('button');
          btn_si.className = 'btn btn-info';
          btn_si.style.marginRight = '30px';
          btn_no.className = 'btn btn-danger';
          btn_no.innerText =  'NO';
          btn_si.innerText = 'SI';
          btn_si.type = btn_no.type = 'button';
          var caracteristica = new Object();
          caracteristica['pregunta'] = datos['mensaje'];
          caracteristica['plantas'] = datos['id_plantas'];
          if(datos['resultados'] == 0){
            cargar_mensaje(datos['mensaje'],1);  
            btn_si.onclick = function(){
                user_input.innerHTML = '';
                caracteristica['respuesta'] = 1;
                caracteristicas.push(caracteristica);
                cargar_mensaje('Si',0);
                setTimeout(function(){
                    ask_question();
                },2000);
            }
            btn_no.onclick = function(){
                user_input.innerHTML = '';
                caracteristica['respuesta'] = 0;
                caracteristicas.push(caracteristica);
                cargar_mensaje('No',0);
                setTimeout(function(){
                    ask_question();
                },2000);

            }

           setTimeout(function(){
            user_input.appendChild(btn_si);
            user_input.appendChild(btn_no);
        },3000);
          }
    });
}
}


// $.ajax({
//     type: "POST",
//     url: BASE_URL + 'Plantas/Consulta',
// }).done(function(datos) {
//     var data = JSON.parse(datos);
//     $("#example1").DataTable({
//         "data": data,
//         "columns": [{
//             "data": "nombre_comun"
//         }, {
//             "data": "nombre_cientifico"
//         }, {
//             "data": "descripcion"
//         }, {
//             data: function(data) {
//                 return ('<td class="text-center">' + '<a href="javascript:void(0)" style="margin-right: 5px;background: #4dbdbd !important;" class="btn bg-info ver-popup" title="Ver" type="button" data-toggle="modal" data-target="#ver">' + '<i class="fa fa-eye"></i>' + "</a>" + "</td>");
//             },
//         }],
//         "responsive": true,
//         "autoWidth": false,
//         "ordering": true,
//         "info": true,
//         "processing": true,
//         "pageLength": 10,
//         "lengthMenu": [5, 10, 20, 30, 40, 50, 100],
//         "buttons": ["copy", "excel", "pdf", "print", "colvis"]
//     }).container().appendTo('#example1_wrapper .col-md-6:eq(0)');
// }).fail(function() {
//     alert("error");
// });
// $(document).on("click", "#guardar", function() {
//     var datos = {
//         nombre_comun: document.getElementById("nombre_comun").value,
//         nombre_cientifico: document.getElementById("nombre_cientifico").value,
//         descripcion: document.getElementById("descripcion").value,
//     };
//     $.ajax({
//         type: "POST",
//         url: BASE_URL + "Plantas/Registrar",
//         data: {
//             datos: datos,
//             sql: "SQL_05",
//         },
//     }).done(function(datos) {
//         if (datos == 1) {
//             swal({
//                 title: "Registrado!",
//                 text: "El elemento fue Registrado con exito.",
//                 type: "success",
//                 showConfirmButton: false
//             });
//             setTimeout(function() {
//                 location.reload();
//             }, 2000);
//         } else {
//             swal({
//                 title: "ERROR!",
//                 text: datos,
//                 type: "error",
//                 html: true,
//                 showConfirmButton: true,
//                 customClass: "bigSwalV2",
//             });
//         }
//     }).fail(function() {
//         alert("error");
//     });
// });