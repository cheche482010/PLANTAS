<?php

trait Componentes
{
    protected function Conexion()
    {
        return [
            'Mysql' => array(
                'Servidor'   => 'mysql',
                'Host'       => 'localhost',
                'Base_Datos' => 'reino_plantae',
                'Puerto'     => '3306',
                'Usuario'    => 'root',
                'Contraseña' => 'root',
            ),
            'ByHost' => array(
                'Servidor'   => 'mysql',
                'Host'       => 'sql112.byethost5.com',
                'Base_Datos' => 'b5_33489354_conco',
                'Puerto'     => '3306',
                'Usuario'    => 'b5_33489354',
                'Contraseña' => 'Cheche482010@',
            )
        ];
    }
}
