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
                    $this->conexion->beginTransaction(); # Inicia una transacción
                    $this->resultado = $this->conexion->executeQuery($this->sentencia)->fetchAllAssociative();
                    $this->conexion->commit();
                    $this->Manager()->close();
                    return $this->resultado;
                    break;
                case '1': #tipo 1 ejecuta un INSERT , UPDATE, DELETE  retorna a true (si no hay falla)
                    $this->conexion->beginTransaction();
                    $this->DBAL = $this->conexion->executeUpdate($this->sentencia, $this->datos);
                    $this->conexion->commit();
                    $this->Manager()->close();
                    return $this->DBAL > 0;
                    return true;
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
        return "SELECT p.cedula_persona, p.fecha_nacimiento FROM personas p WHERE p.estado = 1 ORDER BY p.cedula_persona ASC";
    }

}
