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

                    if(count($caracteristicas) == 4 || count($caracteristicas) == 8 || count($caracteristicas) == 12){
                        $coincidencias = $this->get_coincidencias($caracteristicas);
                    } 

                    if(count($preguntas_bd) > 0 ){
                        $pregunta = $preguntas_bd[array_rand($preguntas_bd)];
                        $result = [
                            'resultados' => 0,
                            'mensaje' => $pregunta['pregunta'],  
                            'id_plantas' => $pregunta['id_plantas'],
                            'resp' => json_encode($coincidencias) 
                       ];
                    }
                    
               
                // if(count($preguntas_bd) == 0){
                //     $pregunta = $preguntas_bd[array_rand($preguntas_bd)];
                // }

                // $result = [
                //     'resultados' => 0,
                //     'mensaje' => $pregunta['pregunta'],   
                // ];

                // if(!$existe) {
                //     $checking = false;
                //     $result = [
                //         'resultados' => 0,
                //         'mensaje' => $pregunta['pregunta'],   
                //     ];
                // }
                // else {
                //     $pregunta = $this->datos[array_rand($this->datos)];
                // }
        }
        echo json_encode($result);
        
    }

    public function get_coincidencias($caracteristicas){
        $this->modelo->_Tipo_(0);
        $this->modelo->_SQL_("SQL_03");
        $plantas = $this->modelo->Administrar();
        $descartados = [];
        $plantas_descartadas = [];
        $coincidencias = [];
        foreach ($caracteristicas as $c){
            if($c['respuesta'] == 0){
                $descartados[] = $c;
            }
        }

        foreach($descartados as $d) {
            $plantas_d = explode('/',$d['id_plantas']);
            $existe = 0;
            for($i=0 ; $i<count($plantas_d) ; $i++){
                for ($j = 0 ;  $j < count($plantas_descartadas) ; $j++){
                    if($plantas_d[$i] == $plantas_descartadas[$j]){
                        $existe++;
                    }
                }
                if( $existe == 0 ){
                    $plantas_descartadas[] = $plantas_d[$i];
                }
            }
        }
         
        foreach ( $plantas as $p ){
            $descartado = false;
            for($i = 0; $i<count($plantas_descartadas) ; $i++){
                if($plantas_descartadas[$i] == $p['id_plantas']){
                    $descartado = true;
                }
            }

            if(!$descartado) {
                $coincidencias[] = $p;
            }
        }

        return $coincidencias;
        
    }

}
