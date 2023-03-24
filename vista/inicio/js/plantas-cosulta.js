window.onload = function(){
var chat = document.getElementById('chat');
var user_input = document.getElementById('user-input');
var caracteristicas = [];
//var coincidencias = [];

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
          else{
            var mensaje_ia = '';
            console.log(datos['resp'][0]);
            for (var i = 0; i< datos['resp'].length; i++){
                if(datos['resp'][i]['descripcion']!=''){
                    mensaje_ia ='';
                }
            }
            cargar_mensaje(mensaje_ia,1);  
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
