<?php

class Plantas extends Controlador
{

    public function __construct()
    {
        parent::__construct();
    }

    public function Cargar_Vistas()
    {   
        $this->vista->Cargar_Vistas('inicio/index');
    }

    // public function Registrar()
    // {
    //     $this->modelo->_Datos_($_POST['datos']);
    //     $this->modelo->_SQL_($_POST['sql']);
    //     $this->modelo->_Tipo_(1);
    //     echo $this->modelo->Administrar();
    // }

    public function get_question(){
        $caracteristicas = $_POST['caracteristicas'];
        $this->modelo->_Tipo_(0);
        $this->modelo->_SQL_("SQL_06");
        $this->datos = $this->modelo->Administrar();

        $result = '';
        $resultados = 0;

        if($caracteristicas == '' || $caracteristicas == null){
            $pregunta = $this->datos[array_rand($this->datos)];
            $result = [
                'resultados' => 0,
                'mensaje' => $pregunta['pregunta'],  
                'id_plantas' => $pregunta['id_plantas'],
                'resp' => json_encode([])
            ];
        }
        else{
            $preguntas_bd = $this->datos;
            $coincidencias = '';
  
                foreach($caracteristicas as $c){
                       for($i = 0; $i <= count( $preguntas_bd ) ; $i++ ){
                           if($c['pregunta'] == $preguntas_bd[$i]['pregunta']){
                            if (($clave = array_search($preguntas_bd[$i], $preguntas_bd)) !== false) {
                                unset($preguntas_bd[$clave]);
                            }
                            
                           }
                       }
                    }

                    if((count($caracteristicas) % 3) == 0){
                        $coincidencias = $this->get_coincidencias($caracteristicas);
                        foreach($coincidencias as $c){
                            if($c['descripcion'] != ''){
                                $resultados = 1;
                            }
                        }
                    } 

                    if(count($preguntas_bd) > 0 ){
                        $pregunta = $preguntas_bd[array_rand($preguntas_bd)];
                        $result = [
                            'resultados' => $resultados,
                            'mensaje' => $pregunta['pregunta'],  
                            'id_plantas' => $pregunta['id_plantas'],
                            'resp' => json_encode($coincidencias) 
                       ];
                    }
        }
        echo json_encode($result);
        
    }

    public function get_coincidencias($caracteristicas){
        $this->modelo->_Tipo_(0);
        $this->modelo->_SQL_("SQL_03");
        $plantas = $this->modelo->Administrar();
        $caracteristicas_planta = [];
        $coincidencias = [];
        foreach ($caracteristicas as $c){
            if($c['respuesta'] == 1){
                $caracteristicas_planta[] = $c;
            }
        }
        
        foreach($caracteristicas_planta as $cp){
             $ids = explode('/',$cp['plantas']);

             if(count($coincidencias) ==0){
                for($i = 0; $i < count($ids) ; $i++){
                    $coincidencias[] = [
                        'id' => $ids[$i],
                        'cantidad' => 1
                    ];
                }
             }
             else{
                $existe = 0;
                    for($i = 0; $i < count($ids) ; $i++){
                        for($n=0; $n<count($coincidencias) ;$n++){
                            if($ids[$i] == $coincidencias[$n]['id']){
                                $coincidencias[$n]['cantidad'] = $coincidencias[$n]['cantidad'] + 1;
                                $existe++;
                            }
                        }
                        if($existe == 0){
                            $coincidencias[] = [
                                'id' => $ids[$i],
                                'cantidad' => 1
                            ];
                        }
                    }
             }

        }
        
        $num_aux=0;
        foreach($coincidencias as $c){
            if($c['cantidad'] > $num_aux){ $num_aux = $c['cantidad'];}
        }

        for( $n=0; $n < count($coincidencias); $n++){
            if($coincidencias[$n]['cantidad'] == $num_aux){ 
                if($num_aux > 1 || count($coincidencias) == 1){
                for($i = 0 ; $i<count($plantas) ; $i++){
                    if($plantas[$i]['id_plantas'] == $coincidencias[$n]['id']){
                        $coincidencias[$n]['descripcion'] = $plantas[$i]['descripcion'];
                    }
                }
            }
            }
            else{
                $coincidencias[$n]['descripcion'] = '';
            }
        }

        return $coincidencias;
        
    }


    public function get_planta(){
        $id_planta = $_POST['id'];
        $this->modelo->_Tipo_(0);
        $this->modelo->_SQL_("SQL_07");
        $this->modelo->_Id_($id_planta);
        $plantas = $this->modelo->Administrar();
        $mensaje = 'Su planta es: '.$plantas[0]['nombre_comun'].' ('.$plantas[0]['nombre_cientifico'].'), ubicada en el habitat '.$plantas[0]['nombre'];
        echo $mensaje;
    }

    public function insert_planta(){
        $this->modelo->_Datos_($_POST['datos']);
        $this->modelo->_SQL_('SQL_05');
        $this->modelo->_Tipo_(1);
        $result = $this->modelo->Administrar();
        if($result){
        $this->modelo->_Tipo_(0);
        $this->modelo->_SQL_("SQL_03");
        $plantas = $this->modelo->Administrar();
        $ultima = $plantas[count($plantas) - 1];
        $this->modelo->_Tipo_(0);
        $this->modelo->_SQL_("SQL_06");
        $preguntas_bd =  $this->modelo->Administrar();

        $preguntas[] = [
            'pregunta' => '¿Su planta es de tamaño '.$ultima['tamaño'].'?',
            'tipo' => 'tamanio',
            'id'   => $ultima['id_plantas']
        ];

        $preguntas[] = [
            'pregunta' => '¿Su planta tiene forma '.$ultima['forma'].'?',
            'tipo' => 'forma',
            'id'   => $ultima['id_plantas']
        ];

        $preguntas[] = [
            'pregunta' => '¿Su planta es de textura '.$ultima['textura'].'?',
            'tipo' => 'textura',
            'id'   => $ultima['id_plantas']
        ];

        $preguntas[] = [
            'pregunta' => '¿Su planta es de color '.$ultima['color'].'?',
            'tipo' => 'color',
            'id'   => $ultima['id_plantas']
        ];

        $preguntas[] = [
            'pregunta' => '¿La hoja de su planta es '.$ultima['tamaño'].'?',
            'tipo' => 'hojas',
            'id'   => $ultima['id_plantas']
        ];

        $preguntas[] = [
            'pregunta' => '¿Su planta posee un tronco '.$ultima['tamaño'].'?',
            'tipo' => 'tronco',
            'id'   => $ultima['id_plantas']
        ];

        $preguntas[] = [
            'pregunta' => '¿Su planta posee flores '.$ultima['flores'].'?',
            'tipo' => 'flores',
            'id'   => $ultima['id_plantas']
        ];
        
        foreach($preguntas as $p){
            $existe = 0;
            foreach($preguntas_bd as $pbd) {
                if(strstr($pbd['pregunta'], $p['pregunta'])){
                    $existe = ['id' => $pbd['id_pregunta'], 'id_plantas' => $pbd['id_plantas']];
                }
            }

            if($existe == 0){
                $this->modelo->_Datos_([
                    'pregunta' => $p['pregunta'],
                    'id_plantas' =>$p['id']
                ]
            );
                $this->modelo->_SQL_('SQL_08');
                $this->modelo->_Tipo_(1);
                $result = $this->modelo->Administrar();
                echo $result;
            }
            else{
                $this->modelo->_Datos_([
                    'id_plantas' =>$existe['id_plantas'].'/'.$p['id'],
                    'id_pregunta' => $existe['id']
                ]
            );
            $this->modelo->_SQL_('SQL_09');
            $this->modelo->_Tipo_(1);
            $result = $this->modelo->Administrar();
            echo $result;
            }
        }
    }

}
}
