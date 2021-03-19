<?php

    /*
     * Obtiene los alumnos que han respondido un examen en base a un curso
     */

    session_start();

    include("../comunes/funciones.php");

    $idCurso = filter_input(INPUT_POST, "idCurso");
    $idModulo = filter_input(INPUT_POST, "idModulo");

    $buscaCurso = "";



    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    if($idCurso > 0){
        $buscaCurso = " and idCurso = " . $idCurso;
    }

     if($idModulo > 0){
        $buscaCurso = " and idModulo = " . $idModulo;
    }

    // Consulta base de datos

    $alumnos_BD = consulta($conexion, "SELECT * FROM alumno m where m.id in (select idAlumno from examenresumen e where 1 = 1 " . $buscaCurso. ") ORDER BY nombre");

    // Forma el XML resultante

    $xml = "<alumnos>";

    while ($alumno = obtenResultado($alumnos_BD)) {
        $xml = $xml . "<alumno>";
        $xml = $xml . "<id>" . $alumno["id"] . "</id>";
        $xml = $xml . "<nombre>" . $alumno["nombre"] . " " . $alumno["apellido"] .  "</nombre>";
        $xml = $xml . "</alumno>";
    }

    $xml = $xml . "</alumnos>";

    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);

    // Regresa el xml resultante

    echo $xml;
?>