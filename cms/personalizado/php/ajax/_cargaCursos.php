<?php

    /*
     * Obtiene los diferentes cursos para ser utilizados en el sistema
     */

    session_start();

    include("../comunes/funciones.php");

    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    // Consulta base de datos

    $cursos_BD = consulta($conexion, "SELECT * FROM curso ORDER BY nombre");

    // Forma el XML resultante

    $xml = "<cursos>";

    while ($curso = obtenResultado($cursos_BD)) {
        $xml = $xml . "<curso>";
        $xml = $xml . "<id>" . $curso["id"] . "</id>";
        $xml = $xml . "<nombre>" . $curso["nombre"] . "</nombre>";
        $xml = $xml . "</curso>";
    }

    $xml = $xml . "</cursos>";

    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);

    // Regresa el xml resultante

    echo $xml;
?>