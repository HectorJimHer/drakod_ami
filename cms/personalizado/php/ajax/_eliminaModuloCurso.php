<?php
    /*
     * Obtiene los status de atencion a prospecto por parte de Call Center
     */

    session_start();

    include("../comunes/funciones.php");

    // Obtiene parametros de request

    $idCurso = filter_input(INPUT_POST, "idCurso");
    $idModulo = filter_input(INPUT_POST, "idModulo");

    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    // Borra modulo del curso

    consulta($conexion, "DELETE FROM curso_modulo WHERE idCurso = " . $idCurso . " AND idModulo = " . $idModulo);

    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);
?>