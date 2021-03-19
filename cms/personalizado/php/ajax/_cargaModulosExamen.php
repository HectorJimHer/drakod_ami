<?php

    /*
     * Obtiene los diferentes modulos que alumnos han respondido examen en base a un curso
     */

    session_start();

    include("../comunes/funciones.php");

    $idCurso = filter_input(INPUT_POST, "idCurso");
    $buscaCurso = "";



    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    if($idCurso > 0){
        $buscaCurso = " and idCurso = " . $idCurso;
    }

    // Consulta base de datos

    $modulos_BD = consulta($conexion, "SELECT * FROM modulo m where m.id in (select idModulo from examenresumen e where 1 = 1 " . $buscaCurso. ") ORDER BY nombre");

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