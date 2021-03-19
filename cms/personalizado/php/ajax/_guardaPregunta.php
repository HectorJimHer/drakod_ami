<?php
    /*
     * Guarda la información de una pregunta
     */

    session_start();

    include("../comunes/funciones.php");

    // Obtiene parametros de request

    $id = filter_input(INPUT_POST, "id");
    $tipoRespuesta = filter_input(INPUT_POST, "tipoRespuesta");
    $orden = filter_input(INPUT_POST, "orden");
    $pregunta = filter_input(INPUT_POST, "pregunta");
    $respuesta1_texto = filter_input(INPUT_POST, "respuesta1_texto");
    $respuesta2_texto = filter_input(INPUT_POST, "respuesta2_texto");
    $respuesta3_texto = filter_input(INPUT_POST, "respuesta3_texto");
    $respuesta4_texto = filter_input(INPUT_POST, "respuesta4_texto");
    $respuesta5_texto = filter_input(INPUT_POST, "respuesta5_texto");
    $respuesta6_texto = filter_input(INPUT_POST, "respuesta6_texto");
    $respuesta7_texto = filter_input(INPUT_POST, "respuesta7_texto");
    $respuesta8_texto = filter_input(INPUT_POST, "respuesta8_texto");
    $respuesta9_texto = filter_input(INPUT_POST, "respuesta9_texto");
    $respuesta10_texto = filter_input(INPUT_POST, "respuesta10_texto");

    $respuestaCorrecta = filter_input(INPUT_POST, "respuestaCorrecta");

    // Obtiene conexion a base de datos

    $conexion = obtenConexion();


    $updatePregunta = "update pregunta set
                        tipoRespuesta = '" . $tipoRespuesta . "'
                        , orden = " .  $orden . "
                        , pregunta = '" .  $pregunta . "'
                        , respuesta1_texto = '" .  $respuesta1_texto . "'
                        , respuesta2_texto = '" .  $respuesta2_texto . "'
                        , respuesta3_texto = '" .  $respuesta3_texto . "'
                        , respuesta4_texto = '" .  $respuesta4_texto . "'
                        , respuesta5_texto = '" .  $respuesta5_texto . "'
                        , respuesta6_texto = '" .  $respuesta6_texto . "'
                        , respuesta7_texto = '" .  $respuesta7_texto . "'
                        , respuesta8_texto = '" .  $respuesta8_texto . "'
                        , respuesta9_texto = '" .  $respuesta9_texto . "'
                        , respuesta10_texto = '" .  $respuesta10_texto . "'
                        where id = " . $id;

    consulta($conexion, $updatePregunta);

    //Se limpian las respuestas

    $updateRespuestas = "update pregunta set
                         respuesta1_correcta = 0
                        , respuesta2_correcta = 0
                        , respuesta3_correcta = 0
                        , respuesta4_correcta = 0
                        , respuesta5_correcta = 0
                        , respuesta6_correcta = 0
                        , respuesta7_correcta = 0
                        , respuesta8_correcta = 0
                        , respuesta9_correcta = 0
                        , respuesta10_correcta = 0
                        where id = " . $id;

    consulta($conexion, $updateRespuestas);

    if($tipoRespuesta == "Opcion"){

        $updateCorrecta = "update pregunta set " . $respuestaCorrecta . " = 1 where id = " . $id;
        consulta($conexion, $updateCorrecta);

    }

    echo "ok";

    liberaConexion($conexion);
?>