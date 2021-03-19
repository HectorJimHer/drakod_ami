<?php

    /*
     * Obtiene los status de atencion a prospecto por parte de Call Center
     */

    session_start();

    include("../comunes/funciones.php");

    // Obtiene parametros de request

    $idArchivo = filter_input(INPUT_POST, "idArchivo");

    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    // Borra archivo del curso

    consulta($conexion, "DELETE FROM curso_archivo WHERE id = " . $idArchivo);

    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);
?>