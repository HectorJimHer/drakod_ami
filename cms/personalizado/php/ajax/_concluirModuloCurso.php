<?php

    /*
     * Obtiene los status de atencion a prospecto por parte de Call Center
     */

    session_start();

    include("../comunes/funciones.php");

    // Obtiene parametros de request

    $idCurso = filter_input(INPUT_POST, "idCurso");
    $idModulo = filter_input(INPUT_POST, "idModulo");
    $idAlumno = filter_input(INPUT_POST, "idAlumno");    

    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    // Se actualiza el registo como concluido

    consulta($conexion, "UPDATE alumno_curso_modulo SET concluido = '" . 1 . "'
                         WHERE idCurso = " . $idCurso . " AND idAlumno = " . $idAlumno . "
                         AND idModulo = " . $idModulo);

                         //Se busca el elemento actualizado para obtener su orden
                         $orden_modulo = consulta($conexion, "SELECT * from alumno_curso_modulo
                                      WHERE concluido = 1 AND idCurso = " . $idCurso . " AND idAlumno = " . $idAlumno . " AND idModulo = " . $idModulo);
                        
                                      $ob_orden_modulo = obtenResultado($orden_modulo);
                                      $orden = $ob_orden_modulo["orden"];

    $siguiente = intval($orden) + 1;
    // Se avtiva el siguiente modulo
    consulta($conexion, "UPDATE alumno_curso_modulo SET activo = '" . 1 . "'
                         WHERE idCurso = " . $idCurso . " AND idAlumno = " . $idAlumno ."
                         AND orden = " . $siguiente .
                         " and idModulo in  (select idModulo from curso_modulo where idCurso = " . $idCurso . ")");


    // se obtiene el modulo siguiente activo
    $modulo_BD = consulta($conexion, "SELECT idModulo from alumno_curso_modulo
                                      WHERE activo = 1 and orden = " . $siguiente . " AND idCurso = " . $idCurso . " AND idAlumno = " . $idAlumno . " order by orden");

    if($modulo = obtenResultado($modulo_BD)){
        $id = $modulo["idModulo"];
    }else{
        $id = 0;
    }

    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);
    echo   $id;
?>