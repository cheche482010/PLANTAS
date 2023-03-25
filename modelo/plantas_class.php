<?php
require_once "modelo/entidad/plantas.php";

use App\Modelo\Entidad\Plantas_Entidad;

class Plantas_Modelo extends Modelo
{
    #Public: acceso sin restricción.
    #Private:Solo puede ser accesado por la clase que lo define.

    private $SQL; #nombre de la sentencia SQL que se ejecutara en el modelo
    private $tipo; #tipo de peticion que usaremos 1/0
    private $DBAL; #sentecia sql iniciada con prepare
    private $sentencia; #sentencia sql que se ejecutara
    private $datos; #datos a ejecutar para enviar a la bd

    private $nombre_comun;
    private $nombre_cientifico;
    private $familia_cientifica;
    private $descripcion;
    private $tamanio;
    private $forma;
    private $textura;
    private $color;
    private $hojas; 
    private $tronco;
    private $flores;
    private $id_habitats;
    private $id;

    public $resultado; #resultado de consultas de la bd

    public function __construct()
    {parent::__construct();}

    // SETTER estaablece los datos a usar en el modelo (tipo void no retornan un valor)
    public function _SQL_(string $SQL): void
    {$this->SQL = $SQL;}
    public function _Tipo_(int $tipo): void
    {$this->tipo = $tipo;}
    public function _Datos_(array $datos): void
    {$this->datos = $datos;}

    public function _ID_(int $id): void
    {$this->id = $id;}

    public function _DatosPlantas_(array $info) : void{
        $this->nombre_comun = $info['nombre_comun'];
        
        $this->nombre_cientifico = $info['nombre_cientifico'];
        
        $this->descripcion = $info['descripcion'];
        
        $this->textura = $info['textura'];
        
        $this->color = $info['color'];
        
        $this->tamanio = $info['tamanio'];
        
        $this->hojas = $info['hojas'];
        
        $this->tronco = $info['tronco'];
        
        $this->flores = $info['flores'];
        
        $this->forma = $info['forma'];
        
        $this->familia_cientifica = $info['familia_cientifica'];
        
        $this->id_habitats = $info['habitat'];
    }

    public function Get()
    {
        $this->plantas = $this->Manager()->getRepository(Plantas_Entidad::class)->findAll();
        return $this->plantas;
    }

    public function Administrar()
    {
        $this->sentencia = $this->{$this->SQL}(); #funcion anonima en espera de asignar nombre
        try {
            switch ($this->tipo) {
                case '0': #tipo 0 trae consultas de la bd retorna a un array con los datos
                    $this->resultado = $this->conexion->executeQuery($this->sentencia)->fetchAllAssociative();
                    $this->Manager()->close();
                    return $this->resultado;
                    break;
                case '1': #tipo 1 ejecuta un INSERT , UPDATE, DELETE  retorna a true (si no hay falla)
                    $this->DBAL = $this->conexion->executeUpdate($this->sentencia, $this->datos);
                    $this->Manager()->close();
                    return $this->DBAL > 0;
                    return true;
                    break;
                case '2':           
                    $this->resultado = $this->conexion->executeQuery($this->sentencia, $this->datos)->fetchAllAssociative();
                    $this->Manager()->close();
                    return $this->resultado;
                    break;
                default: # mensaje error si la peticion fue incorrecta
                    die('[Error 400] => "La Peticion es Incorrecta, solo se permite peticion de tipo 0/1."');
                    break;
            }

        } catch (\Doctrine\DBAL\Exception $e) {
            #capturamos el error y se envia la respuesta(ubicacion MODELO)
            return $this->Capturar_Error($e, "Plantas");
        }
    }

    private function SQL_01(): string
    {
        return "SELECT plantas.id, plantas.nombre_comun, plantas.nombre_cientifico
        FROM plantas 
        JOIN relaciones ON plantas.id = relaciones.planta_id
        JOIN caracteristicas ON relaciones.caracteristica_id = caracteristicas.id
        WHERE plantas.habitat_id = $habitat_id AND caracteristicas.$caracteristica = $valor";
    }

    private function SQL_02(): string
    {
        return "SELECT p.* FROM plantas p JOIN relaciones r ON p.id = r.planta_id JOIN caracteristicas c ON r.caracteristica_id = c.id WHERE p.habitat_id = :habitad AND c.nombre = :caracteristicas";
    }

    private function SQL_03(): string
    {
        return "SELECT * FROM plantas";
    }

    private function SQL_04(): string
    {
        return "SELECT p.* FROM plantas p JOIN relaciones r ON p.id = r.planta_id JOIN caracteristicas c ON r.caracteristica_id = c.id JOIN plantas_habitats ph ON ph.planta_id = p.id JOIN habitats h ON h.id = ph.habitat_id WHERE h.nombre = :habitad AND c.nombre = :caracteristicas";
    }

    private function SQL_05(): string
    {
        return "INSERT INTO plantas (nombre_comun, nombre_cientifico, familia_cientifica, descripcion, tamaño, forma, textura, color, hojas, tronco, flores, id_habitats) 
        VALUES (:nombre_comun, :nombre_cientifico, :familia_cientifica,
        :descripcion, :tamanio, :forma, :textura, :color, :hojas, :tronco, :flores, :id_habitats);";
    }

    private function SQL_06(): string
    {
        return "SELECT * FROM preguntas";
    }

    private function SQL_07(): string
    {
        return "SELECT * FROM plantas p, habitats h WHERE p.id_plantas = $this->id and p.id_habitats = h.id_habitats";
    }

    private function SQL_08(): string
    {
        return "INSERT INTO preguntas (pregunta, respuesta,id_plantas) 
        VALUES (:pregunta,1,:id_plantas);";
    }

    private function SQL_09(): string
    {
        return "UPDATE preguntas SET id_plantas = :id_plantas WHERE id_pregunta = :id_pregunta";
    }
}
