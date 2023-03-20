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

    public function limitedDFS($habitad, $caracteristicass, $current_depth, $max_depth, $plants_found)
    {
        if ($current_depth > $max_depth) {
            return $plants_found; // Si alcanzamos la profundidad máxima, devolvemos las plantas encontradas hasta el momento.
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
            $plants_found[] = $row['nombre_comun'];
        }

        // Llamamos a la función recursivamente para buscar plantas más específicas
        foreach ($caracteristicass as $caracteristicas) {
            $plants_found = $this->limitedDFS($habitad, [$caracteristicas], $current_depth + 1, $max_depth, $plants_found);
        }

        return $plants_found;
    }

    public function limitedBFS($habitad, $caracteristicass, $max_depth)
    {
        $plants_found = [];

        // Inicializamos la cola con el nodo raíz
        $queue = [[$habitad, $caracteristicass, 0]];

        while (!empty($queue)) {
            // Obtenemos el siguiente nodo de la cola
            $node            = array_shift($queue);
            $habitad         = $node[0];
            $caracteristicass = $node[1];
            $current_depth   = $node[2];

            // Si hemos alcanzado la profundidad máxima, dejamos de buscar en esta rama
            if ($current_depth > $max_depth) {
                continue;
            }

            // Creamos la consulta para obtener las plantas que coincidan con los criterios de búsqueda
            $query = "SELECT p.* FROM plantas p JOIN relaciones r ON p.id = r.planta_id JOIN caracteristicas c ON r.caracteristica_id = c.id WHERE p.habitad_id = '$habitad'";
            foreach ($caracteristicass as $caracteristicas) {
                $query .= " AND c.nombre = '$caracteristicas'";
            }

            // Ejecutamos la consulta y obtenemos las plantas
            $result = mysqli_query($conexion, $query);

            // Agregamos las plantas encontradas al array
            while ($row = mysqli_fetch_assoc($result)) {
                $plants_found[] = $row['nombre_comun'];
            }

            // Obtenemos los hábitats que están conectados al actual
            $query  = "SELECT DISTINCT h.nombre FROM habitads h JOIN relaciones r ON h.id = r.habitad_id JOIN plantas p ON r.planta_id = p.id WHERE p.habitad_id = '$habitad'";
            $result = mysqli_query($conexion, $query);

            // Agregamos los nodos de los hábitats conectados a la cola
            while ($row = mysqli_fetch_assoc($result)) {
                $next_habitad = $row['nombre'];
                $queue[]      = [$next_habitad, $caracteristicass, $current_depth + 1];
            }

            // Obtenemos las características que están conectadas a las actuales
            foreach ($caracteristicass as $caracteristicas) {
                $query  = "SELECT DISTINCT c.nombre FROM caracteristicas c JOIN relaciones r ON c.id = r.caracteristica_id JOIN plantas p ON r.planta_id = p.id WHERE p.habitad_id = '$habitad' AND c.nombre != '$caracteristicas'";
                $result = mysqli_query($conexion, $query);

                // Agregamos los nodos de las características conectadas a la cola
                while ($row = mysqli_fetch_assoc($result)) {
                    $next_caracteristicas = $row['nombre'];
                    $queue[]             = [$habitad, array_merge($caracteristicass, [$next_caracteristicas]), $current_depth + 1];
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
        $habitad         = "Bosque";
        $caracteristicass = ["Color", "Luz indirecta"];
        $max_depth       = 3;
        $current_depth   = 0;

        $plants_found = $this->limitedDFS($habitad, $caracteristicass, $current_depth, $max_depth, []);
        // Imprimimos las plantas encontradas
        foreach ($plants_found as $plant) {
            echo $plant . "<br>";
        }
    }

    public function prueba2()
    {
        // Llamamos a la función y especificamos el hábitat, características y profundidad máxima
        $habitad         = "Bosque";
        $caracteristicass = "Color";
        $max_depth       = 3;

        $plants_found = $this->limitedBFS($habitad, $caracteristicass, $max_depth);

        // Imprimimos las plantas encontradas
        foreach ($plants_found as $plant) {
            echo $plant . "<br>";
        }
    }

}
