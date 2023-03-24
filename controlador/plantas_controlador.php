<?php

class Plantas extends Controlador
{

    public function __construct()
    {
        parent::__construct();
    }

    public function Consulta(){
        $this->modelo->_Tipo_(0);
        $this->modelo->_SQL_("SQL_03");
        $this->datos = $this->modelo->Administrar();
        echo json_encode($this->datos);
    }

    public function limitedDFS($habitad, $caracteristicass, $nodo_actual, $nivel_profundidad, $encontrados)
    {
        if ($nodo_actual > $nivel_profundidad) {
            return $encontrados; // Si alcanzamos la profundidad máxima, devolvemos las plantas encontradas hasta el momento.
        }

        // Creamos la consulta para obtener las plantas que coincidan con los criterios de búsqueda
        $this->modelo->_SQL_("SQL_04");
        $this->modelo->_Tipo_(2);
        $this->modelo->_Datos_([
            "habitad"        => $habitad,
            "caracteristicas" => $caracteristicass[0],
        ]);
        $result = $this->modelo->Administrar();

        // Agregamos las plantas encontradas al array
        foreach ($result as $row) {
            $encontrados[] = $row['nombre_comun'];
        }

        // Llamamos a la función recursivamente para buscar plantas más específicas
        foreach ($caracteristicass as $caracteristicas) {
            $encontrados = $this->limitedDFS($habitad, [$caracteristicas], $nodo_actual + 1, $nivel_profundidad, $encontrados);
        }

        return $encontrados;
    }

    public function Cargar_Vistas()
    {
        $this->vista->Cargar_Vistas('inicio/index');
    }

    public function prueba()
    {
        $habitad         = "Bosque";
        $caracteristicass = ["Color", "Luz indirecta"];
        $nivel_profundidad       = 3;
        $nodo_actual   = 0;

        $encontrados = $this->limitedDFS($habitad, $caracteristicass, $nodo_actual, $nivel_profundidad, []);
        // Imprimimos las plantas encontradas
        foreach ($encontrados as $plant) {
            echo $plant . "<br>";
        }
    }

    public function Registrar()
    {
        $this->modelo->_Datos_($_POST['datos']);
        $this->modelo->_SQL_($_POST['sql']);
        $this->modelo->_Tipo_(1);
        echo $this->modelo->Administrar();
    }

    public function get_question(){
        $caracteristicas = $_POST['caracteristicas'];
        $this->modelo->_Tipo_(0);
        $this->modelo->_SQL_("SQL_06");
        $this->datos = $this->modelo->Administrar();

        $this->modelo->_SQL_("SQL_03");
        $plantas = $this->modelo->Administrar();
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

                    if((count($caracteristicas) % 3) ==0){
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
        $posible_resultado = '';
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
                    if($plantas[$i]['id_plantas'] == $c['id']){
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

}
