<?php

    /*
     * Obtiene los diferentes modulos para ser utilizados en el sistema
     */

    session_start();

    include("../comunes/funciones.php");

    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    // Consulta base de datos

    $modulos_BD = consulta($conexion, "SELECT * FROM modulo ORDER BY nombre");

    // Forma el XML resultante

    $xml = "<modulos>";

    while ($modulo = obtenResultado($modulos_BD)) {
        $xml = $xml . "<modulo>";
        $xml = $xml . "<id>" . $modulo["id"] . "</id>";
        $xml = $xml . "<nombre>" . $modulo["nombre"] . "</nombre>";
        $xml = $xml . "</modulo>";
    }

    $xml = $xml . "</modulos>";

    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);

    // Regresa el xml resultante

    echo $xml;
?>