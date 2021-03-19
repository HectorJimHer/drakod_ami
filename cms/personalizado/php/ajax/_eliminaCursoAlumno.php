<?php

    /*
     * Obtiene los status de atencion a prospecto por parte de Call Center
     */

    session_start();

    include("../comunes/funciones.php");

    // Obtiene parametros de request

    $idCurso = filter_input(INPUT_POST, "idCurso");
    $idAlumno = filter_input(INPUT_POST, "idAlumno");

    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    // Borra producto de cotizacion

    //consulta($conexion, "DELETE FROM alumno_evaluacion WHERE idAlumno = " . $idAlumno . " AND idCurso = " . $idCurso);
    //consulta($conexion, "DELETE FROM alumno_curso_modulo WHERE idAlumno = " . $idAlumno . " AND idCurso = " . $idCurso);
    consulta($conexion, "DELETE FROM alumno_curso WHERE idAlumno = " . $idAlumno . " AND idCurso = " . $idCurso);

    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);
?>