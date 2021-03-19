<?php

    /*
     * Habilita un alumno para que pueda acceder al sitio web
     */

    session_start();

    include("../comunes/funciones.php");

    // Obtiene parametros de request

    $id = filter_input(INPUT_POST, "id");
    $habilitado = filter_input(INPUT_POST, "habilitado");

    // Obtiene parametros de sesion

    $idUsuario = $_SESSION["cms_usuario_id"];

    // Inicializa variables

    $fechaActual = date("Y-m-d H:i:s");

    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    // Habilita / inhabilita alumno

    if (!estaVacio($id)) {
        consulta($conexion, "UPDATE alumno SET habilitado = " . $habilitado . " WHERE id = " . $id);
    }

    // Registra evento

    consulta($conexion, "INSERT INTO log (fecha, evento, idUsuario) VALUES ('" . $fechaActual . "', '" . ($habilitado ? "Habilitacion" : "Inhabilitacion") . " de alumno | id = " . $id . "', " . $idUsuario . ")");

    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);

    // Regresa el xml resultante

    echo "ok";
?>