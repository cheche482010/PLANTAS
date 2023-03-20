<?php

class Plantas extends Controlador
{

    public function __construct()
    {
        parent::__construct();
    }

    public function Consulta(){
        
    }

    public function limitedDFS($habitat, $characteristics, $current_depth, $max_depth, $plants_found, $modelo)
    {
        if ($current_depth > $max_depth) {
            return $plants_found; // Si alcanzamos la profundidad máxima, devolvemos las plantas encontradas hasta el momento.
        }

        // Creamos la consulta para obtener las plantas que coincidan con los criterios de búsqueda
        $modelo->_SQL_("SQL_02");
        $modelo->_Tipo_(0);
        $modelo->_Datos_([
            "habitat"        => $habitat,
            "characteristic" => $characteristics[0],
        ]);
        $result = $modelo->Administrar();

        // Agregamos las plantas encontradas al array
        foreach ($result as $row) {
            $plants_found[] = $row['nombre_comun'];
        }

        // Llamamos a la función recursivamente para buscar plantas más específicas
        foreach ($characteristics as $characteristic) {
            $plants_found = limitedDFS($habitat, [$characteristic], $current_depth + 1, $max_depth, $plants_found, $modelo);
        }

        return $plants_found;
    }

    public function limitedBFS($habitat, $characteristics, $max_depth)
    {
        $plants_found = [];

        // Inicializamos la cola con el nodo raíz
        $queue = [[$habitat, $characteristics, 0]];

        while (!empty($queue)) {
            // Obtenemos el siguiente nodo de la cola
            $node            = array_shift($queue);
            $habitat         = $node[0];
            $characteristics = $node[1];
            $current_depth   = $node[2];

            // Si hemos alcanzado la profundidad máxima, dejamos de buscar en esta rama
            if ($current_depth > $max_depth) {
                continue;
            }

            // Creamos la consulta para obtener las plantas que coincidan con los criterios de búsqueda
            $query = "SELECT p.* FROM plantas p JOIN relaciones r ON p.id = r.planta_id JOIN caracteristicas c ON r.caracteristica_id = c.id WHERE p.habitat_id = '$habitat'";
            foreach ($characteristics as $characteristic) {
                $query .= " AND c.nombre = '$characteristic'";
            }

            // Ejecutamos la consulta y obtenemos las plantas
            $result = mysqli_query($conexion, $query);

            // Agregamos las plantas encontradas al array
            while ($row = mysqli_fetch_assoc($result)) {
                $plants_found[] = $row['nombre_comun'];
            }

            // Obtenemos los hábitats que están conectados al actual
            $query  = "SELECT DISTINCT h.nombre FROM habitats h JOIN relaciones r ON h.id = r.habitat_id JOIN plantas p ON r.planta_id = p.id WHERE p.habitat_id = '$habitat'";
            $result = mysqli_query($conexion, $query);

            // Agregamos los nodos de los hábitats conectados a la cola
            while ($row = mysqli_fetch_assoc($result)) {
                $next_habitat = $row['nombre'];
                $queue[]      = [$next_habitat, $characteristics, $current_depth + 1];
            }

            // Obtenemos las características que están conectadas a las actuales
            foreach ($characteristics as $characteristic) {
                $query  = "SELECT DISTINCT c.nombre FROM caracteristicas c JOIN relaciones r ON c.id = r.caracteristica_id JOIN plantas p ON r.planta_id = p.id WHERE p.habitat_id = '$habitat' AND c.nombre != '$characteristic'";
                $result = mysqli_query($conexion, $query);

                // Agregamos los nodos de las características conectadas a la cola
                while ($row = mysqli_fetch_assoc($result)) {
                    $next_characteristic = $row['nombre'];
                    $queue[]             = [$habitat, array_merge($characteristics, [$next_characteristic]), $current_depth + 1];
                }
            }
        }

        return $plants_found;
    }

    public function Cargar_Vistas()
    {
        $this->vista->Cargar_Vistas('inicio/index');
    }

    public function prueba()
    {
        $habitat         = "Bosque";
        $characteristics = ["Suelo húmedo", "Luz indirecta"];
        $max_depth       = 3;
        $current_depth   = 0;

        $plants_found = limitedDFS($habitat, $characteristics, $current_depth, $max_depth, [], $modelo);
        // Imprimimos las plantas encontradas
        foreach ($plants_found as $plant) {
            echo $plant . "<br>";
        }
    }

    public function prueba2()
    {
        // Llamamos a la función y especificamos el hábitat, características y profundidad máxima
        $habitat         = "Bosque";
        $characteristics = ["Suelo húmedo", "Luz indirecta"];
        $max_depth       = 3;

        $plants_found = limitedBFS($habitat, $characteristics, $max_depth);

        // Imprimimos las plantas encontradas
        foreach ($plants_found as $plant) {
            echo $plant . "<br>";
        }
    }

}
