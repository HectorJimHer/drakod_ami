<?php

    /*
     * Obtiene los instructores involucrados en examen en base a un curso
     */

    session_start();

    include("../comunes/funciones.php");

    $idCurso = filter_input(INPUT_POST, "idCurso");
    $buscaCurso = "";



    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    if($idCurso > 0){
        $buscaCurso = " and r.idCurso = " . $idCurso;
    }

    // Consulta base de datos

    $modulos_BD = consulta($conexion, "SELECT * FROM instructor m where m.id in (select idInstructor from curso c inner join examenresumen r on c.id = r.idCurso where 1 = 1 " . $buscaCurso . ") ORDER BY nombre,apellido");

    // Forma el XML resultante

    $xml = "<instructores>";

    while ($instructor = obtenResultado($modulos_BD)) {
        $xml = $xml . "<instructor>";
        $xml = $xml . "<id>" . $instructor["id"] . "</id>";
        $xml = $xml . "<nombre>" . $instructor["nombre"] . " " . $instructor["apellido"] . "</nombre>";
        $xml = $xml . "</instructor>";
    }

    $xml = $xml . "</instructores>";

    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);

    // Regresa el xml resultante

    echo $xml;
?>