<?php

    /*
     * Habilita un curso para ser visible en el sitio web
     */

    session_start();

    include("../comunes/funciones.php");

    // Obtiene parametros de request

    $idExamen = filter_input(INPUT_POST, "idExamen");
    $cuantasPreguntas = filter_input(INPUT_POST, "cuantasPreguntas");
    $idAlumno = filter_input(INPUT_POST, "idAlumno");
    $idCurso = filter_input(INPUT_POST, "idCurso");
    $idModulo = filter_input(INPUT_POST, "idModulo");

    $conexion = obtenConexion();
    $fechaActual = date("Y-m-d H:i:s");
    $promedio = 0;

    $queryPreguntas = "select * from pregunta where idExamen = " . $idExamen . " order by orden";
    $preguntas_BD = consulta($conexion, $queryPreguntas); 
    $aciertos = 0;
    $preguntasOpcion = 0;
    $cuantas = 1;

    while ($pregunta = obtenResultado($preguntas_BD)) {
        
        $respuesta = filter_input(INPUT_POST, "pregunta" . $cuantas);
        if($pregunta["tipoRespuesta"] == 'Opcion'){
            if($pregunta[$respuesta . "_correcta"] == 1){
                $aciertos++;
            }
            $preguntasOpcion++;
        }
        $cuantas++;
    }

    if($preguntasOpcion > 0) {
        $promedio = ($aciertos/$preguntasOpcion) * 100;
    }
     consulta($conexion, "INSERT INTO examenresumen (fechaRegistro, 
                                                    idAlumno, 
                                                    idCurso, 
                                                    idModulo, 
                                                    idExamen, 
                                                    aciertos, 
                                                    errores, 
                                                    calificacion)
                                        values
                                                    ('" . $fechaActual . "',
                                                     " . $idAlumno . ",
                                                     " . $idCurso . ",
                                                     " . $idModulo . ",
                                                     " . $idExamen . ",
                                                     " . $aciertos . ",
                                                     " . ($preguntasOpcion - $aciertos) . ",
                                                     " . $promedio . "
                                                     )");


     $consultaResumen = "select id from examenresumen where idAlumno = " . $idAlumno . " and idCurso = " . $idCurso . " and idModulo = " . $idModulo . " and idExamen = " . $idExamen;

     $resumen_BD = consulta($conexion, $consultaResumen); 
     $resumen = obtenResultado($resumen_BD);

     $idResumen = $resumen["id"];
     $esCorrecto = 0;

     $preguntas_BD = consulta($conexion, $queryPreguntas); 
     $cuantas = 1;
     while ($pregunta = obtenResultado($preguntas_BD)) {
        $esCorrecto = 0;

        $respuesta = filter_input(INPUT_POST, "pregunta" . $cuantas);
        if($pregunta["tipoRespuesta"] == 'Opcion'){
            if($pregunta[$respuesta . "_correcta"] == 1){
                $esCorrecto = 1;
            }
        }else{
            $esCorrecto = 1;
        }

        $insertPregunta = "INSERT INTO examendetalle (idExamenResumen,
                                                      idAlumno,
                                                      idCurso,
                                                      idModulo,
                                                      idPregunta,
                                                      respondioAlumno,
                                                      correcta)
                                            values
                                                     (" . $idResumen . ",
                                                      " . $idAlumno . ",
                                                      " . $idCurso . ",
                                                      " . $idModulo . ",
                                                      " . $pregunta["id"] . ",
                                                      '" . $respuesta . "',
                                                      " . $esCorrecto . "
                                                      )"; 
        consulta($conexion, $insertPregunta);
        $cuantas++;
    }

    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);

    // Regresa el xml resultante

    if($preguntasOpcion > 0) {
        echo "calificacion - " . $promedio;
    }else{
        echo "NA";
    }
?>